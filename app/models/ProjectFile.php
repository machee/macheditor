<?php

interface ProjectFile
{
    public function getContents();

    public function setContents($contents);

    public function delete();
}

