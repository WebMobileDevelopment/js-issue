<header class="header">
<?php 
	$path_current = dirname( __FILE__ ).'/'; // /var/www/subdir
$currentdir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path_current); // /subdir
$contentdir="content/";
$imagedir="images/";
?>
	<div class="topsection1"><img src="<?=$cdnurl;?>/common/images/header_logo_<?php echo strtolower($logo_brand);?>.png" width="200"></div>
</header>

<div class="topbaner" style="background-color:<?=$header_bg_color;?>">
 <div class="leftimage">       	<div onclick="this.nextElementSibling.style.display='block'; this.style.display='none'">
	 <?php if(!empty($header_video))
		{ ?>
	 <div class="video-container"><iframe src="<?php echo $header_video;?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe></div>
	 <?php } else { ?>
   <img src="<?=$cdnurl.$currentdir.$contentdir.$header_image;?>" style="cursor:pointer" />
	<?php } ?>
</div>

        		</div>
            <div class="rightparttext">
            	<div class="textsection">
                    <div class="bigtext"><?=nl2br($header_large_text);?></div>
                    <p><?=nl2br($header_small_text);?></p>
                    <!--<div class="sundaytimes"><img src="images/o2logo.png" width="200"></div>-->
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <?
		if($tplus==1)  
        { ?>
		<div class="bigheader times_member_pc">
        	<div class="iconheader times_member times_member_pc">
            	<img src="images/card-icon.jpg" width="53">
            </div><a href='<?=$tplus_link;?>'>Times+ members have an additional chance to win by cliking here</a>
        </div>
<div class="bigheader times_member">
        	<a href='<?=$tplus_link;?>'><img src="content/<?=$tplus_header;?>"></a>
        </div>
<? } ?>
