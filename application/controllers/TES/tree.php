<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tree extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	public function tree($title,$titulo,$seleccion,$tipo,$menu,$id,$text,$datos)
	{
		$data["title"]=$title;
		$data["titulo"]=str_replace("%20"," ",$titulo);
		$data["seleccion"]=$seleccion;
		$data["tipo"]=$tipo;
		$data["menu"]=$menu;
		$data["id"]=$id;
		$data["text"]=$text;
		$data["treeData"]='[
		{title: "item1 with key and tooltip", tooltip: "Look, a tool tip!" },
		{title: "chiapas", select: true },
		{title: "Folder", isFolder: true, key: "id3",
			children: [
				{title: "Sub-item 3.1",
					children: [
						{title: "Sub-item 3.1.1", key: "id3.1.1" },
						{title: "Sub-item 3.1.2", key: "id3.1.2" }
					]
				},
				{title: "Sub-item 3.2",
					children: [
						{title: "Sub-item 3.2.1", key: "id3.2.1" },
						{title: "Sub-item 3.2.2", key: "id3.2.2" }
					]
				}
			]
		},
		{title: "Document with some children (expanded on init)", key: "id4", expand: true,
			children: [
				{title: "Sub-item 4.1 (active on init)", activate: true,
					children: [
						{title: "Sub-item 4.1.1", key: "id4.1.1" },
						{title: "Sub-item 4.1.2", key: "id4.1.2" }
					]
				},
				{title: "Sub-item 4.2 (selected on init)", select: true,
					children: [
						{title: "Sub-item 4.2.1", key: "id4.2.1" },
						{title: "Sub-item 4.2.2", key: "id4.2.2" }
					]
				},
				{title: "Sub-item 4.3 (hideCheckbox)", hideCheckbox: true },
				{title: "Sub-item 4.4 (unselectable)", unselectable: true }
			]
		}
	];';
		$this->template->write_view('content',DIR_TES.'/tree/tree.php',$data);
		$this->template->render();
	}
}
?>