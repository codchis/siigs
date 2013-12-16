
var ApiGen = ApiGen || {};
ApiGen.elements = [["f","_attributes_to_string()"],["f","_exception_handler()"],["f","_get_smiley_array()"],["f","_get_validation_object()"],["f","_list()"],["f","_parse_attributes()"],["f","_parse_form_attributes()"],["c","Accion"],["c","Accion_model"],["f","alternator()"],["f","anchor()"],["f","anchor_popup()"],["c","ArbolSegmentacion_model"],["c","ArrayAccess"],["c","ArrayObject"],["f","ascii_to_entities()"],["f","auto_link()"],["f","auto_typography()"],["f","base_url()"],["c","Bitacora"],["c","Bitacora_model"],["f","br()"],["f","byte_format()"],["f","camelize()"],["c","Catalogo"],["c","Catalogo_model"],["c","Catalogo_x_raiz"],["c","Catalogo_x_raiz_model"],["c","CatalogoCsv"],["c","CatalogoCsv_model"],["f","character_limiter()"],["c","CI_Benchmark"],["c","CI_Cache"],["c","CI_Cache_apc"],["c","CI_Cache_dummy"],["c","CI_Cache_file"],["c","CI_Cache_memcached"],["c","CI_Calendar"],["c","CI_Cart"],["c","CI_Config"],["c","CI_Controller"],["c","CI_DB_active_record"],["c","CI_DB_Cache"],["c","CI_DB_cubrid_driver"],["c","CI_DB_cubrid_forge"],["c","CI_DB_cubrid_result"],["c","CI_DB_cubrid_utility"],["c","CI_DB_driver"],["c","CI_DB_forge"],["c","CI_DB_mssql_driver"],["c","CI_DB_mssql_forge"],["c","CI_DB_mssql_result"],["c","CI_DB_mssql_utility"],["c","CI_DB_mysql_driver"],["c","CI_DB_mysql_forge"],["c","CI_DB_mysql_result"],["c","CI_DB_mysql_utility"],["c","CI_DB_mysqli_driver"],["c","CI_DB_mysqli_forge"],["c","CI_DB_mysqli_result"],["c","CI_DB_mysqli_utility"],["c","CI_DB_oci8_driver"],["c","CI_DB_oci8_forge"],["c","CI_DB_oci8_result"],["c","CI_DB_oci8_utility"],["c","CI_DB_odbc_driver"],["c","CI_DB_odbc_forge"],["c","CI_DB_odbc_result"],["c","CI_DB_odbc_utility"],["c","CI_DB_pdo_driver"],["c","CI_DB_pdo_forge"],["c","CI_DB_pdo_result"],["c","CI_DB_pdo_utility"],["c","CI_DB_postgre_driver"],["c","CI_DB_postgre_forge"],["c","CI_DB_postgre_result"],["c","CI_DB_postgre_utility"],["c","CI_DB_result"],["c","CI_DB_sqlite_driver"],["c","CI_DB_sqlite_forge"],["c","CI_DB_sqlite_result"],["c","CI_DB_sqlite_utility"],["c","CI_DB_sqlsrv_driver"],["c","CI_DB_sqlsrv_forge"],["c","CI_DB_sqlsrv_result"],["c","CI_DB_sqlsrv_utility"],["c","CI_DB_utility"],["c","CI_Driver"],["c","CI_Driver_Library"],["c","CI_Email"],["c","CI_Encrypt"],["c","CI_Exceptions"],["c","CI_Form_validation"],["c","CI_FTP"],["c","CI_Hooks"],["c","CI_Image_lib"],["c","CI_Input"],["c","CI_Javascript"],["c","CI_Jquery"],["c","CI_Lang"],["c","CI_Loader"],["c","CI_Log"],["c","CI_Migration"],["c","CI_Model"],["c","CI_Output"],["c","CI_Pagination"],["c","CI_Parser"],["c","CI_Profiler"],["c","CI_Router"],["c","CI_Security"],["c","CI_Session"],["c","CI_SHA1"],["c","CI_Table"],["c","CI_Template"],["c","CI_Trackback"],["c","CI_Typography"],["c","CI_Unit_test"],["c","CI_Upload"],["c","CI_URI"],["c","CI_User_agent"],["c","CI_Utf8"],["c","CI_Xmlrpc"],["c","CI_Xmlrpcs"],["c","CI_Zip"],["c","Cie10"],["c","Cie10_model"],["f","config_item()"],["c","Controlador"],["c","Controlador_model"],["c","ControladorAccion_model"],["f","convert_accented_characters()"],["c","Correo"],["c","Countable"],["f","create_captcha()"],["f","current_url()"],["f","days_in_month()"],["f","DB()"],["f","delete_cookie()"],["f","delete_files()"],["f","directory_map()"],["f","do_hash()"],["f","doctype()"],["f","element()"],["f","elements()"],["f","ellipsize()"],["f","encode_php_tags()"],["c","Enrolamiento"],["c","Enrolamiento_model"],["f","entities_to_ascii()"],["f","entity_decode()"],["c","Entorno"],["c","Entorno_model"],["c","Errorlog"],["c","Errorlog_model"],["c","Estado_tableta_model"],["c","Exception"],["f","force_download()"],["f","form_button()"],["f","form_checkbox()"],["f","form_close()"],["f","form_dropdown()"],["f","form_error()"],["f","form_fieldset()"],["f","form_fieldset_close()"],["f","form_hidden()"],["f","form_input()"],["f","form_label()"],["f","form_multiselect()"],["f","form_open()"],["f","form_open_multipart()"],["f","form_password()"],["f","form_prep()"],["f","form_radio()"],["f","form_reset()"],["f","form_submit()"],["f","form_textarea()"],["f","form_upload()"],["f","formatFecha()"],["f","get_clickable_smileys()"],["f","get_config()"],["f","get_cookie()"],["f","get_dir_file_info()"],["f","get_file_info()"],["f","get_filenames()"],["f","get_instance()"],["f","get_mime_by_extension()"],["f","getArray()"],["f","gmt_to_local()"],["c","Grupo"],["c","Grupo_model"],["f","heading()"],["f","highlight_code()"],["f","highlight_phrase()"],["f","html_escape()"],["f","human_to_unix()"],["f","humanize()"],["f","img()"],["f","increment_string()"],["c","Index"],["f","index_page()"],["f","is_false()"],["f","is_loaded()"],["f","is_php()"],["f","is_really_writable()"],["f","is_true()"],["c","IteratorAggregate"],["f","js_insert_smiley()"],["f","lang()"],["f","link_tag()"],["f","load_class()"],["f","local_to_gmt()"],["f","log_message()"],["f","mailto()"],["f","mdate()"],["c","Menu"],["c","Menu_model"],["c","Menubuilder"],["f","meta()"],["f","mysql_to_unix()"],["f","nbs()"],["f","nl2br_except_pre()"],["c","Notificacion"],["c","Notificacion_model"],["f","now()"],["c","Obtenercurp"],["f","octal_permissions()"],["f","ol()"],["f","parse_smileys()"],["c","Permiso"],["c","Permiso_model"],["c","PHPMailer"],["c","phpmailerException"],["f","plural()"],["c","POP3"],["f","prep_url()"],["f","quotes_to_entities()"],["c","Raiz"],["c","Raiz_model"],["f","random_element()"],["f","random_string()"],["f","read_file()"],["f","redirect()"],["f","reduce_double_slashes()"],["f","reduce_multiples()"],["c","ReglaVacuna"],["c","ReglaVacuna_model"],["f","remove_invisible_characters()"],["f","repeater()"],["f","safe_mailto()"],["f","sanitize_filename()"],["f","send_email()"],["c","Serializable"],["c","Servicios"],["c","Session"],["f","set_checkbox()"],["f","set_cookie()"],["f","set_radio()"],["f","set_realpath()"],["f","set_select()"],["f","set_status_header()"],["f","set_value()"],["f","show_404()"],["f","show_error()"],["f","singular()"],["f","site_url()"],["f","smiley_js()"],["c","SMTP"],["f","standard_date()"],["f","strip_image_tags()"],["f","strip_quotes()"],["f","strip_slashes()"],["f","symbolic_permissions()"],["c","Tableta"],["c","Tableta_model"],["f","timespan()"],["f","timezone_menu()"],["f","timezones()"],["c","Tipo_censo_model"],["c","Traversable"],["c","Tree"],["f","trim_slashes()"],["f","ul()"],["f","underscore()"],["f","unix_to_human()"],["f","uri_string()"],["f","url_title()"],["c","Usuario"],["c","Usuario_model"],["c","Usuario_tableta"],["c","Usuario_tableta_model"],["f","valid_email()"],["f","validation_errors()"],["f","word_censor()"],["f","word_limiter()"],["f","word_wrap()"],["f","write_file()"],["f","xml_convert()"],["c","XML_RPC_Client"],["c","XML_RPC_Message"],["c","XML_RPC_Response"],["c","XML_RPC_Values"],["f","xss_clean()"]];
