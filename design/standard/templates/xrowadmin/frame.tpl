<form method="post" action={concat( 'admin/frame/' )|ezurl}>

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Information'|i18n( 'design/admin/content/view/versionview' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{* Object ID *}
<p>
<label>{'Module'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$modulename|wash()}
</p>

<p>
<label>{'View'|i18n( 'design/admin/content/view/versionview' )}:</label>
{$view|wash()}
</p>

{* Manage versions *}
<div class="block">

<input class="button" type="submit" name="Exit" value="{'Exit'|i18n( 'design/admin/content/view/versionview' )}" title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/view/versionview' )}" />

</div>

</div></div></div></div></div></div>

</div>
<br />



</div>

</div>

</form>



<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">Framed Application [{$modulename|wash()} {$view|wash()}]</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr">

<div class="context-information">

<p class="full-screen">

<a href="{concat( $modulename, "/" , $view, "/" )|ezurl(no)}/" target="_blank"><img src={"images/window_fullscreen.png"|ezdesign} /></a>
</p>
<div class="break"></div>
</div>

{* Content preview in content window. *}
<div class="mainobject-window">

    <iframe src="{concat( $modulename, "/" , $view, "/" )|ezurl(no)}/" width="98%" height="800">
    Your browser does not support iframes. Please see this <a href="{concat( $modulename, "/" , $view, "/" )|ezurl(no)}/">link</a> instead.
</iframe>

</div>


</div></div>

{* Buttonbar for content window. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
No actions avialable.
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>



<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
