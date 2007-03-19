<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php 1405 2005-03-21 14:41:41Z guerin $


require_once('pre.php');
var_dump($_REQUEST);
var_dump($_FILES);
var_dump($mon_super_fichier);
require_once('www/file/file_utils.php');

$Language->loadLanguageMsg('file/file');

if (! isset($group_id) || ! $group_id) {
    exit_error($Language->getText('file_file_utils', 'g_id_err'),$Language->getText('file_file_utils', 'g_id_err'));
}
if (!user_ismember($group_id,'R2')) {
    exit_permission_denied();
}

file_utils_admin_header(array('title'=>$Language->getText('file_admin_editpackages','release_edit_f_rel'), 'help' => 'FileReleaseDelivery.html'));

?><h2>Files Administration</h2>
<h3><a href="manageprocessors.php?group_id=<?=$group_id?>">Manage List of Processors</a></h3>
<p>Manage list of processors</p>
<?php

file_utils_footer(array());
?>
