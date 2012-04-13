<?php
class TemplateFile
{
	public $source;
	public $destination;
	public $single;

	public function TemplateFile($row = "")
	{
		$cols = explode("\t",trim($row));
		if (count($cols) != 3) throw new Exception("Invalid row Paramter: " . $row);

		$this->source = $cols[0];
		$this->destination = $cols[1];
		$this->generate_mode = $cols[2];
	}
}