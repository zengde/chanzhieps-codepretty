<?php
/**
 * The common/header file of blog module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     blog
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
$webRoot   = isset($config->site->cdn) ? $config->site->cdn . $config->webRoot : $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "template/default/theme/";
$navs = $this->tree->getChildren(0, 'blog');
?>
<!DOCTYPE html>
<?php if(!empty($config->oauth->sina)):?>
<html xmlns:wb="http://open.weibo.com/wb">
<?php else:?>
<html lang="en">
<?php endif;?>
<head>
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Cache-Control" content="no-transform" />
  <?php
  if(!isset($title))    $title    = ''; 
  if(!empty($title))    $title   .= $lang->minus;
  if(!isset($keywords)) $keywords = $config->site->keywords;
  if(!isset($desc))     $desc     = $config->site->desc;

  echo html::title($title . $config->site->name);
  echo html::meta('keywords',    strip_tags($keywords));
  echo html::meta('description', strip_tags($desc));
  if(isset($this->config->site->meta)) echo $this->config->site->meta;

  css::import($webRoot . 'zui/css/min.css');
  css::import($themeRoot . 'common/style.css');
  css::import($jsRoot    . 'jquery/treeview/min.css');

  /* Import customed css file if it exists. */
  $siteCustomCssFile = $this->app->getDataRoot() . 'css' . DS . $this->config->site->code . DS . $this->config->template->name . DS . $this->config->template->theme . DS . 'style.css';
  if($this->config->multi && file_exists($siteCustomCssFile))
  {
      css::import(sprintf($webRoot . 'data/css/%s/%s/%s/style.css?' . $this->config->template->customVersion, $config->site->code, $config->template->name, $config->template->theme));
  }
  else
  {
      $customCssFile = $this->app->getDataRoot() . 'css' . DS . $this->config->template->name . DS . $this->config->template->theme . DS . 'style.css';
      if(file_exists($customCssFile)) css::import(sprintf($webRoot . 'data/css/%s/%s/style.css?' . $this->config->template->customVersion, $config->template->name, $config->template->theme));
       
  }

  js::exportConfigVars();
  if($config->debug)
  {
      js::import($jsRoot . 'jquery/min.js');
      js::import($jsRoot . 'zui/min.js');
      js::import($jsRoot . 'chanzhi.js');
      js::import($jsRoot . 'jquery/treeview/min.js');
      js::import($jsRoot . 'my.js');
  }
  else
  {
      js::import($jsRoot . 'all.js');
  }

  if(isset($pageCSS)) css::internal($pageCSS);

  echo isset($this->config->site->favicon) ? html::icon(json_decode($this->config->site->favicon)->webPath) : html::icon($webRoot . 'favicon.ico');
  echo html::rss($this->createLink('rss', 'index', '', '', 'xml'), $config->site->name);
  js::set('lang', $lang->js);
  
	/*codepretty*/
  	css::import($jsRoot    . 'prettify/prettify.css');
	js::import($jsRoot . 'prettify/prettify.js');

?>
<?php
if(!empty($config->oauth->sina)) $sina = json_decode($config->oauth->sina);
if(!empty($config->oauth->qq))   $qq   = json_decode($config->oauth->qq);
if(!empty($sina->verification)) echo $sina->verification; 
if(!empty($qq->verification))   echo $qq->verification;
if(empty($sina->verification) && !empty($sina->widget)) js::import('http://tjs.sjs.sinajs.cn/open/api/js/wb.js');
?>
<!--[if lt IE 9]>
<?php
if($config->debug)
{
    js::import($jsRoot . 'html5shiv/min.js');
    js::import($jsRoot . 'respond/min.js');
}
else
{
    js::import($jsRoot . 'all.ie8.js');
}
?>
<![endif]-->
<!--[if lt IE 10]>
<?php
if($config->debug)
{
    js::import($jsRoot . 'jquery/placeholder/min.js');
}
else
{
    js::import($jsRoot . 'all.ie9.js');
}
?>
<![endif]-->
<?php if(isset($this->config->site->basestyle)) css::internal($this->config->site->basestyle);?>
<script>
$(function(){
	$('pre.prettyprint').addClass('linenums')
	prettyPrint();

})
</script>
</head>
<body>
<div class='page-container page-blog'>
  <header id='header' class='clearfix'>
    <div id='headNav'><div class='wrapper'><?php echo commonModel::printTopBar();?></div></div>
    <div id='headTitle'>
      <div class="wrapper">
        <?php if(isset($config->site->logo)):?>
        <?php $logo = json_decode($config->site->logo);?>
        <div id='siteLogo'>
          <?php echo html::a($this->config->webRoot, html::image($logo->webPath, "class='logo' title='{$this->config->company->name}'"));?>
        </div>
        <?php else: ?>
        <div id='siteName'><h2><?php echo $config->site->name;?></h2></div>
        <?php endif;?>
      </div>
    </div>
    <?php if(commonModel::isAvailable('search')):?>
    <div id='searchbar'>
      <form action='<?php echo helper::createLink('search')?>' method='get' role='search'>
        <div class='input-group'>
          <?php $keywords = ($this->app->getModuleName() == 'search') ? $this->session->serachIngWord : '';?>
          <?php echo html::input('words', $keywords, "class='form-control' placeholder=''");?>
          <?php if($this->config->requestType == 'GET') echo html::hidden($this->config->moduleVar, 'search') . html::hidden($this->config->methodVar, 'index');?>
          <div class='input-group-btn'>
            <button class='btn btn-default' type='submit'><i class='icon icon-search'></i></button>
          </div>
        </div>
      </form>
    </div>
    <?php endif;?>
  </header>
  <nav id="blogNav" class="navbar navbar-default" role="navigation">
    <div class='wrapper'>
      <ul class="nav navbar-nav">
        <li <?php if(empty($category)) echo "class='active'"?>>
           <?php echo html::a($this->inlink('index'), (isset($this->config->site->type) and $this->config->site->type == 'blog') ? $lang->home : $lang->blog->home)?>
        </li>
        <?php 
        foreach($navs as $nav)
        {
          isset($category->id) ? $categoryID = $category->id : $categoryID = 0;
          $class = $nav->id == $categoryID ? "class='nav-blog-$nav->id active'" : "class='nav-blog-$nav->id'";
          echo "<li {$class}>" . html::a($this->inlink('index', "id={$nav->id}", "category={$nav->alias}"), $nav->name) . '</li>';
        }
        ?>
      </ul>
      <?php if(!isset($this->config->site->type) or $this->config->site->type != 'blog'):?>
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo html::a($config->webRoot, '<i class="icon-home icon-large"></i> ' . $lang->blog->siteHome);?></li>
      </ul>
      <?php endif;?>
    </div>
  </nav>
  <div class='page-wrapper'>
    <div class='page-content'>
