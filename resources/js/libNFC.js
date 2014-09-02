/**
 * Clase libNFC
 * Encapsula la comunicacion con el WebSocket
 * 
 * @param {type} $
 * @returns {libNFC}
 */
libNFC = function($) 
{
    // Objeto WebSocket
    this.socket = null;
    // Servidor con el que se establece la conexion
    this.host = 'ws://localhost:8082';
    // Si se establece a true, muestra mensajes en cada evento
    this.debug = true;
    // Mensaje a mostrar en el log
    this.msj = '';
    // Variable bandera para determinar si ocurrio algun error
    this.error = false;
    // Posibles valores del atributo readyState del objeto WebSocket
    this.CONNECTING = 0; //The connection is not yet open.
    this.OPEN       = 1; //The connection is open and ready to communicate.
    this.CLOSING    = 2; //The connection is in the process of closing.
    this.CLOSED     = 3; //The connection is closed or couldn't be opened.
    
    // Mensaje de los posibles valores del atributo readyState del objeto WebSocket
    this.msjError = new Array(
            'Conectando...',
            'Conexion abierta y lista para comunicar',
            'Cerrando conexion...',
            'La conexion esta cerrada o no se pudo establecer'
            );
    /**
     * Close codes
     * 
     * https://developer.mozilla.org/en-US/docs/Web/API/CloseEvent
     * http://www.iana.org/assignments/websocket/websocket.xml
     */
    this.CLOSE_CODES = new Array();
    this.CLOSE_CODES[1000] = 'CLOSE_NORMAL';                     // Normal closure; the connection successfully completed whatever purpose for which it was created.
    this.CLOSE_CODES[1001] = 'CLOSE_GOING_AWAY';                 // The endpoint is going away, either because of a server failure or because the browser is navigating away from the page that opened the connection.
    this.CLOSE_CODES[1002] = 'CLOSE_PROTOCOL_ERROR';             // The endpoint is terminating the connection due to a protocol error.
    this.CLOSE_CODES[1003] = 'CLOSE_UNSUPPORTED';                // The connection is being terminated because the endpoint received data of a type it cannot accept (for example, a text-only endpoint received binary data).
    this.CLOSE_CODES[1004] = 'CLOSE_RESERVED';                   // A meaning might be defined in the future.
    this.CLOSE_CODES[1005] = 'CLOSE_NO_STATUS';                  // Indicates that no status code was provided even though one was expected.
    this.CLOSE_CODES[1006] = 'CLOSE_ABNORMAL';                   // Used to indicate that a connection was closed abnormally (that is, with no close frame being sent) when a status code is expected.
    this.CLOSE_CODES[1007] = 'CLOSE_INVALID_FRAME_PAYLOAD_DATA'; // The endpoint is terminating the connection because a message was received that contained inconsistent data (e.g., non-UTF-8 data within a text message).
    this.CLOSE_CODES[1008] = 'CLOSE_POLICY_VIOLATION';           // The endpoint is terminating the connection because it received a message that violates it's policy. This is a generic status code, used when codes 1003 and 1009 are not suitable.
    this.CLOSE_CODES[1009] = 'CLOSE_TOO_LARGE';                  // The endpoint is terminating the connection because a data frame was received that is too large.
    this.CLOSE_CODES[1010] = 'CLOSE_MANDATORY_EXTENSION';        // The client is terminating the connection because it expected the server to negotiate one or more extension, but the server didn't.
    this.CLOSE_CODES[1011] = 'CLOSE_INTERNAL_ERROR';             // The server is terminating the connection because it encountered an unexpected condition that prevented it from fulfilling the request.
    this.CLOSE_CODES[1012] = 'CLOSE_SERVICE_RESTART';            // The server is restarted or restarting
    this.CLOSE_CODES[1013] = 'CLOSE_TRY_AGAIN_LATER';            // The server is not avaliable
    this.CLOSE_CODES[1015] = 'CLOSE_TLS_HANDSHAKE';              // Indicates that the connection was closed due to a failure to perform a TLS handshake (e.g., the server certificate can't be verified).
    
    // Comandos
    
    this.READ_COMMAND  = "r\n";
    this.WRITE_COMMAND = "w";
    
    /**
     * Conecta con el servidor
     * 
     * @returns {this}
     */
    this.connect = function()
    {
        try {
            fechaHora = new Date();
            this.msj = 'Estableciendo conexion...';
            
            if (window.WebSocket) {
                this.socket = new WebSocket(this.host);
            } else if (window.MozWebSocket) {
                this.socket = new MozWebSocket(this.host);
            } else {
                this.msj += '\r\nERROR: El navegador no soporta WebSocket';
                this.error = true;
            }
            
            if (this.socket !== null) {
                parent = this;
                this.error = false;
                
                this.socket.onopen = function()
                {
                    fechaHora = new Date();
                    parent.msj = 'Conexion exitosa';

                    if (parent.debug) {
                        console.log(fechaHora.toLocaleString()+' onopen: '+parent.msj);
                    }
                };
                
                this.socket.onclose = function(event)
                {
                    fechaHora = new Date();
                    parent.msj = 'Conexion cerrada. '+event.code+': '+(event.reason == '' ? parent.CLOSE_CODES[event.code] : event.reason);

                    if (parent.debug) {
                        console.log(fechaHora.toLocaleString()+' onclose: '+parent.msj);
                    }
                };

                this.socket.onerror = function()
                {
                    fechaHora = new Date();
                    parent.msj = 'Error = '+parent.msjError[parent.socket.readyState];
                    this.error = true;

                    if (parent.debug) {
                        console.log(fechaHora.toLocaleString()+' onerror: '+parent.msj);
                    }
                };
                
                this.socket.onmessage = function(event)
                {
                    fechaHora = new Date();
                    parent.msj = 'Mensaje recibido = ['+event.data+'] Longitud del mensaje = ['+event.data.length+']';

                    if (parent.debug) {
                        console.log(fechaHora.toLocaleString()+' onmessage: '+parent.msj);
                    }
                };
                
            } else {
                fechaHora = new Date();
                this.msj += ' ERROR no se pudo crear el WebSocket';
            }

            if (this.debug) {
                fechaHora = new Date();
                console.log(fechaHora.toLocaleString()+' connect: '+this.msj);
            }
        } catch (error) {
            this.error = true;
            
            if (this.debug) {
                fechaHora = new Date();
                console.log(fechaHora.toLocaleString()+' connect [error]: '+error);
            }
        }
        
        return this;
    };
    
    /**
     * Envia mensajes
     * 
     * @param {string} datos
     * @returns {this}
     */
    this.send = function(datos)
    {
        try {
            fechaHora = new Date();
            this.msj = 'Enviando mensaje... ';
            this.msj += 'Contenido del mensaje = ['+datos+'] ';
            this.msj += 'Longitud del mensaje = ['+datos.length+'] ';
            
            if (this.socket.readyState === WebSocket.OPEN) {
                this.socket.send(datos);
                this.msj += 'Mensaje enviado exitosamente';
                this.error = false;
            } else {
                this.msj += 'ERROR el socket no esta disponible';
                this.error = true;
            }

            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' send: '+this.msj);
            }
        } catch (error) {
            this.error = true;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' send [error]: '+error);
            }
        }
        
        return this;
    };
    
    /**
     * Cierra la conexion
     * 
     * @returns {this}
     */
    this.close = function()
    {
        try {
            fechaHora = new Date();
            this.msj = 'Cerrando conexion... ';
            
            this.socket.close();
            this.error = false;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' close: '+this.msj);
            }
        } catch (error) {
            this.error = true;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' close [error]: '+error);
            }
        }
        
        return this;
    };
    
    /**
     * Envia el comando 'read' al servidor socket
     * 
     * @returns {this}
     */
    this.read = function()
    {
        try {
            fechaHora = new Date();
            this.msj = 'Comando read enviado';
            
            this.send(this.READ_COMMAND);
            this.error = false;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' read: '+this.msj);
            }
        } catch (error) {
            this.error = true;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' read [error]: '+error);
            }
        }
        
        return this;
    };
    
    /**
     * Envia el comando 'write' al servidor socket
     * junto con los datos a guardar en la tarjeta NFC
     * 
     * datos string Cadena de texto que se desea guardar en la tarjeta NFC
     * 
     * @returns {this}
     */
    this.write = function(datos)
    {
        try {
            fechaHora = new Date();
            this.msj = 'Comando write enviado';
            
            this.send(this.WRITE_COMMAND + "" + datos + "\n");
            this.error = false;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' write: '+this.msj);
            }
        } catch (error) {
            this.error = true;
            
            if (this.debug) {
                console.log(fechaHora.toLocaleString()+' write [error]: '+error);
            }
        }
        
        return this;
    };
};