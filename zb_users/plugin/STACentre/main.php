<?php
require '../../../zb_system/function/c_system_base.php';

require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}

if (!$zbp->CheckPlugin('STACentre')) {$zbp->ShowError(48);die();}

$blogtitle='静态管理中心';

if(count($_POST)>0){

	$zbp->option['ZC_STATIC_MODE'] =trim(GetVars('ZC_STATIC_MODE','POST'));
	$zbp->option['ZC_ARTICLE_REGEX'] =trim(GetVars('ZC_ARTICLE_REGEX','POST'));
	$zbp->option['ZC_PAGE_REGEX']=trim(GetVars('ZC_PAGE_REGEX','POST'));
	$zbp->option['ZC_INDEX_REGEX']=trim(GetVars('ZC_INDEX_REGEX','POST'));
	$zbp->option['ZC_CATEGORY_REGEX']=trim(GetVars('ZC_CATEGORY_REGEX','POST'));
	$zbp->option['ZC_TAGS_REGEX']=trim(GetVars('ZC_TAGS_REGEX','POST'));	
	$zbp->option['ZC_DATE_REGEX']=trim(GetVars('ZC_DATE_REGEX','POST'));
	$zbp->option['ZC_AUTHOR_REGEX']=trim(GetVars('ZC_AUTHOR_REGEX','POST'));	
	$zbp->SaveOption();

	$zbp->BuildModule_Add('previous');
	$zbp->BuildModule_Add('calendar');
	$zbp->BuildModule_Add('comments');
	$zbp->BuildModule_Add('catalog');
	$zbp->BuildModule_Add('authors');
	$zbp->BuildCache();
	$zbp->SetHint('good');
	Redirect('./list.php');
}



$ua=array(

'ZC_ARTICLE_REGEX' => array(
'{%host%}view.php?id={%id%}',
'{%host%}post/{%id%}.html',
'{%host%}post/{%alias%}.html',
),


'ZC_PAGE_REGEX' => array(
'{%host%}view.php?id={%id%}',
'{%host%}{%id%}.html',
'{%host%}{%alias%}.html',
),


'ZC_INDEX_REGEX' => array(
'{%host%}?page={%page%}',
'{%host%}page_{%page%}.html',
'{%host%}page_{%page%}/',
'{%host%}page_{%page%}',
),


'ZC_CATEGORY_REGEX' =>array(
 '{%host%}?cate={%id%}&page={%page%}',
 '{%host%}category-{%id%}_{%page%}.html',
 '{%host%}category-{%alias%}_{%page%}.html',
 '{%host%}category/{%id%}/{%page%}/',
 '{%host%}category/{%alias%}/{%page%}',
),


 
'ZC_TAGS_REGEX' => array(
 '{%host%}?tags={%alias%}&page={%page%}',
 '{%host%}tags-{%id%}_{%page%}.html',
 '{%host%}tags-{%alias%}_{%page%}.html',
),


'ZC_DATE_REGEX' =>array(
 '{%host%}?date={%date%}&page={%page%}',
 '{%host%}date-{%date%}_{%page%}.html',
 '{%host%}post/{%date%}_{%page%}.html',
),


'ZC_AUTHOR_REGEX' =>array(
 '{%host%}?auth={%id%}&page={%page%}',
 '{%host%}author-{%id%}_{%page%}.html',
 '{%host%}author/{%id%}/{%page%/}',
),


);

function CreateOptoinsOfUrl($type){
	global $ua,$zbp;
	$s='';
	$d='style="display:none;"';
	if($zbp->option['ZC_STATIC_MODE']=='ACTIVE'){
		$r='disabled="disabled"';
	}else{
		$r='';
	}
$r='';
	foreach ($ua[$type] as $key => $value) {
		$s .= '<p '.$d.'><label><input '.$r.' type="radio" name="radio'.$type.'" value="'.$value.'" onclick="$(\'#'.$type.'\').val($(this).val())" />&nbsp;' . $value . '</label></p>';
		$d='';
	}

	echo $s;

}

require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"> <a href="main.php"><span class="m-left m-now">配置页面</span></a><a href="list.php"><span class="m-left">ReWrite规则</span></a><a href="help.php"><span class="m-right">帮助</span></a> </div>
  <div id="divMain2" class="edit category_edit">
	<form id="edit" name="edit" method="post" action="#">
<input id="reset" name="reset" type="hidden" value="" />
<table border="1" class="tableFull tableBorder">
<tr>
	<th class="td20"><p align='left'><b>·静态化选项</b><br><span class='note'>&nbsp;&nbsp;使用伪静态前必须确认主机是否支持</span></p></th>
	<th>
<p><label><input type="radio" <?php echo $zbp->option['ZC_STATIC_MODE']=='ACTIVE'?'checked="checked"':'' ?> value="ACTIVE" name="ZC_STATIC_MODE" onchange="changeOptions(0);" /> &nbsp;&nbsp;动态</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label><input type="radio" <?php echo $zbp->option['ZC_STATIC_MODE']=='ACTIVE'?'':'checked="checked"' ?>  value="REWRITE"  name="ZC_STATIC_MODE" onchange="changeOptions(1);" />&nbsp;&nbsp;伪静态</label></p>
	</th>
	</tr>
<tr>
	<td><p align='left'><b>·文章的URL配置</b></p></td>
	<td><input id='ZC_ARTICLE_REGEX' name='ZC_ARTICLE_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_ARTICLE_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_ARTICLE_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·页面的URL配置</b></p></td>
	<td><input id='ZC_PAGE_REGEX' name='ZC_PAGE_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_PAGE_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_PAGE_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·首页的URL配置</b></p></td>
	<td><input id='ZC_INDEX_REGEX' name='ZC_INDEX_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_INDEX_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_INDEX_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·分类页的URL配置</b></p></td>
	<td><input id='ZC_CATEGORY_REGEX' name='ZC_CATEGORY_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_CATEGORY_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_CATEGORY_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·标签页的URL配置</b></p></td>
	<td><input id='ZC_TAGS_REGEX' name='ZC_TAGS_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_TAGS_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_TAGS_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·日期页的URL配置</b></p></td>
	<td><input id='ZC_DATE_REGEX' name='ZC_DATE_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_DATE_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_DATE_REGEX') ?></td>
</tr>
<tr>
	<td><p align='left'><b>·作者页的URL配置</b></p></td>
	<td><input id='ZC_AUTHOR_REGEX' name='ZC_AUTHOR_REGEX' style='width:500px;' type='text' value='<?php echo $zbp->option['ZC_AUTHOR_REGEX']?>'></td>
</tr>
<tr>
	<td></td>
	<td><?php CreateOptoinsOfUrl('ZC_AUTHOR_REGEX') ?></td>
</tr>
<?php

?>
</table>
	  <hr/>
	  <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
	  </p>

	</form>
	<script type="text/javascript">
function changeOptions(i){
	$('input[name^=ZC_]').each(function(){
		var s='radio' + $(this).prop('name');
		$(this).val( $("input[type='radio'][name='"+s+"']").eq(i).val() );
	});
	if(i=='0'){
		$("input[name^='radio']").prop('disabled',true);
		$("input[name='ZC_STATIC_MODE']").val('ACTIVE');
	}else{
		$("input[name^='radio']").prop('disabled',false);
		$("input[name='ZC_STATIC_MODE']").val('REWRITE');
	}

}
	</script>
	<script type="text/javascript">ActiveLeftMenu("aPluginMng");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/STACentre/logo.png';?>");</script>	
  </div>
</div>


<?php
require $blogpath . 'zb_system/admin/admin_footer.php';

RunTime();
?>