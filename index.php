<?php

ini_set( 'session.cookie_httponly', 1 );
ini_set( 'session.cookie_secure', 1 );
header('X-Frame-Options: SAMEORIGIN');
//define('RE_CAPTCHA_KEY','6LdNmBcUAAAAAGog5m1E-NNnwt18YzR3zIhT2FCY');
//define ('API_URL',"https://mp-staging.co.uk/master/api/v1/idcheck/[1]");
//$cdnurl="https://1967642195.rsc.cdn77.org";
include('content/gb.php');
require('local_config.php');
require(ROOT . 'common/db/DB_manager.php');
		//Value from the files
		$answer2f= utf8_decode($answer2);
		$answer1f= utf8_decode($answer1);
$time_now = date('Y-m-d H:i:s');
$min_date = date('2001-01-01');
$unique_code= htmlspecialchars($_GET["code"]); 

include('../config/connect.php');
$db = new DB_manager(HOST, DBU, DBPASS, DB);

if($time_now >= $stime && $time_now <= $etime)
 {

	$verify_captcha=true;
	$ip =$_SERVER['REMOTE_ADDR'];
	$caller = $_POST['caller'];

	if($caller=='Submit' && $_POST['user_token'] == $_SESSION['user_token'])
	{
		$title = $_POST['title'];
		$id_cod = $db->escape(strip_tags($_POST['answer2']));
		$answer2 = $db->escape(strip_tags($_POST['answer2']));
		$answer1 = $db->escape(strip_tags($_POST['answer1']));
		$answer3 = $db->escape(strip_tags($_POST['answer3']));
		$fname = $db->escape(strip_tags($_POST['fname']));
		$lname = $db->escape(strip_tags($_POST['lname']));
		$dob = $db->escape(strip_tags($_POST['date_of_birth']));
		$gender = $db->escape(strip_tags($_POST['gender']));
		$house_number = $db->escape(strip_tags($_POST['house_number']));
		$address1 = $db->escape(strip_tags($_POST['address1']));
		$address2 = $db->escape(strip_tags($_POST['address2']));
		$street = $db->escape(strip_tags($_POST['street']));
		$area = $db->escape(strip_tags($_POST['area']));
		$city = $db->escape(strip_tags($_POST['city']));
		$post_code = $db->escape(strip_tags($_POST['post_code']));
		$email = $db->escape(strip_tags($_POST['email']));
		$confirm_email = $db->escape(strip_tags($_POST['confirm_email']));
		$telephone= $db->escape(strip_tags($_POST['telephone']));
		$mobile = $db->escape(strip_tags($_POST['mobile']));
		$saturday_frequency = $db->escape(strip_tags($_POST['saturday_frequency']));
		$sunday_frequency = $db->escape(strip_tags($_POST['sunday_frequency']));
		$cultureplusmember = $db->escape(strip_tags($_POST['cultureplusmember']));
		$thetimes = $db->escape(strip_tags($_POST['thetimes']));
		$contact_by_other = $db->escape(strip_tags($_POST['contact_by_other']));
		$requested_tickets = $db->escape(strip_tags($_POST['requested_tickets']));
		$unique_code = trim($db->escape(strip_tags($_POST['unique_code'])));
		$terms_agreed = $db->escape(strip_tags($_POST['terms_agreed']));
		$errors=array();

		if($ucode_req==1 || $ucode_req==2)
		if(empty($unique_code))
			$errors['unique_code']='Please enter unique code';
		if($question1!="")
			if(empty($answer1))
				$errors['answer1']='Please answer question 1';
		if($question2!="") //changed on 21st Dec , chnaged from if(!question! )
			if(empty($answer2))
					$errors['answer2']='Please answer question 2';
		if($question3!="") //changed on 21st Dec , chnaged from if(!question! )
			if(empty($answer3))
					$errors['answer3']='Please answer question 3';
		if($show_dob==1)
			if(!valid_date($dob))
				$errors['age']='Please enter valid date of birth :'.$dob;
			elseif($min_dob>1)
			{
				$max_date = date('Y-m-d',strtotime("-$min_dob year"));
				if($dob>$max_date)
					$errors['age']="Minimum age require to enter is $mind_dob year";
			}
				
		if(empty($fname))
			$errors['fname']='Please enter first name';
		if(empty($lname))
			$errors['lname']='Please enter last name';
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors['email']='Please enter valid Email address';
		if($email!=$confirm_email)
			$errors['confirm_email']='Please confirm your email address as it does not appear to match correctly';
		if($show_address)
		{
		if(empty($house_number))
			$errors['houseno']='Please enter House name/number';
		if(empty($street))
			$errors['street']='Please enter Street';
		if(empty($city))
			$errors['city']='Please enter City';
		if(empty($post_code))
			$errors['post_code']='Please enter post code';
		}

		if($show_phone)
			if(empty($telephone))
				if(strlen(trim($telephone))<10)
					$errors['telephone']='Please enter valid Phone number';
		
		if($terms_agreed!='agree')
			$errors['terms']='Please accept our terms and conditions';
		$contact_by_email = 3;
		$contact_by_sms = 4;

		if(substr($_POST['telephone'],0,2)=="07"  )
			$contact_by_sms = 3;

		$contact_by_us = 4;

		if($_POST['post_code']!="")
			$contact_by_us = 3;

		//check for duplicate email
		if($verify_captcha)
		{
			//include("../captcha/securimage.php");
			//$img = new Securimage();
			//if(!$img->check($_POST['ccode']))
			//	 $errors['captcha']="Must enter valid verification code to enter the competition";
			
			$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?remoteip=".$ip."&secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
			$response = json_decode($response, true);
			if($response["success"] != true)
				$errors['captcha']="Please tick and confirm the box I'm not robot.";
		}
		// block the email domain if there in blocked email list
		$block_eml_array=array("movenextweb.com","mobiledatamail.com","stickique.co.uk","stickique.com","awesomemail.us","squeezer.us","sarcasticcharm.com","cupcaker.us","instantmoviestream.net","emailbaker.us","course-manager.co.uk","tangerineinternet.com","doiamuseyou.com","navyngrey.com","seriouslyforreal.com","instantmoviestream.net","indigoable.net","misao.me","tigerweb.org.uk","nutmail.info","darklin.info","purpleweb.info","2rainmail.org.uk","absoluteweb.info","2rainmail.org.uk","wipenet.info","barchor.org.uk","cannotmail.org.uk","crymet.org.uk","drecom01.co.uk","freggnet.co.uk","hoodmail.co.uk","kreahnet.org.uk","lonynet.oeg.uk","mailbreaker.co.uk","moussenetmail.co.uk","mywheelbox.org.uk","pluntermail.org.uk","prainnet.org.uk","rackernet.org.uk","railosnet.co.uk","rottmail.co.uk","runracemail.org.uk","runwaynet.org.uk","sherrymail.co.uk","shortsmail.co.uk","stonetimenet.co.uk","telph1line.org.uk","threemailnet.co.uk","tyermail.org.uk","wonandron.co.uk","wormail.co.uk","freemailstore.com","bestmailforyou.co.uk","easybusinessemail.info","yourmail4you.com");
		$eml_arr=@explode("@",$email);
		if(@in_array($eml_arr[1],$block_eml_array)==true)
			$errors['email']=" <h3>Sorry, this email address is not a valid email. Please correct your email address to continue.</h3>";

	  //check for duplicate email
	 // $q="select sum(no_of_tickets) as cust_booked_tickets from hourly_competition1 where vEmail='$email'";
		$unique_code_on_record='';
		$already_entered =false;
		if(count($errors)==0)
		{
			$db->from($table);
			$db->where(array('Code'=>$Code));
			$db->open_where();
			$db->where(array('Email_Address'=> $email));
			$db->or_where(array('Booking_Id'=>$unique_code));
			$db->close_where();			
			$db->order_by('id', 'desc');
			$rowqr=$db->fetch_first();
			
			 // $q="select sum(no_of_tickets) as cust_booked_tickets from hourly_competition1 where vEmail='$email'";
			 //$q="select * from $table where Code='$Code' AND (Email_Address='$email' OR Booking_Id='$unique_code') order by id desc";
			 //$qr=mysql_query($q) or die("<b>Error occured<br>Query:</b>$q2<br>".mysql_error());
			 //if(mysql_affected_rows()>0)

			 if ($db->affected_rows > 0)
			 {
				if($rowqr['Email_Address']!=$email)
				{
					$errors['unique_code']='Sorry, the code you entered has already been used';
				}
				elseif($rowqr['Booking_Id']!=$unique_code)
				{
					$errors['unique_code']='Sorry, you have already entered this competition using different code.';
				}
				elseif($is_hourly_comp)
				{
					 // check for the duplicate email within an hour
					$lastdt=$rowqr['Registration_Date'];
					$timenow=date('H');
					$timereg=date('H',strtotime($lastdt));
					$entery_date = date('Y-m-d');
					if($timenow==$timereg && $lastdt > $entery_date)
					{	
						$already_entered =true;						
						$unique_code_on_record = $rowqr['Booking_Id'];
					}	 
				}
				else
				{	
					$already_entered = true;
					$unique_code_on_record = $rowqr['Booking_Id'];
				}
			}
		}
	  //$total_cust_booked_tickets = $rowqr['cust_booked_tickets'];
		//if($total_cust_booked_tickets>0)
	   //{

	   //$errors['email']="Sorry you have already applied for a ticket for this offer you cannot apply for any more";
	 // }
	 $code_id =0;
	 if(count($errors)==0 && !empty($unique_code) )
	 if($ucode_req==2)
	 { 
		 
		$API_URL = str_replace('[1]',$unique_code,$API_URL);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $API_URL);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$result_vars = json_decode($result);
		
		if(empty($result_vars->promo_id) || $Code!=$result_vars->promo_id || $result_vars->volume<1)
		{
			$errors['unique_code']="Sorry, the code you entered is not a valid code ";//{$Code}!={$result_vars->promo_id}";
		}

	 }
	  elseif($ucode_req==1)
	  {
		  	//$q="select * from $unique_code_table where code='$unique_code'";			
			$db->from($unique_code_table);
			$db->where(array('code'=>$unique_code));			
			$r=$db->fetch_first();
 			if($db->affected_rows > 0)
		  	{
				if($already_entered)
				{
					if(strtolower($unique_code_on_record) != strtolower($unique_code) )
					{
						$errors['unique_code']='Sorry, You have already registered, using a different unique code. Please re-enter your details with the correct code or use a different valid email address.';
					}	
				}
				else
				{	
					$code_id = $r['id'];
					if($r['user_id']>0)
						$errors['unique_code']='Sorry, the code you entered has already been used';
					elseif( $r['valid_from']> $time_now)
						$errors['unique_code']='Sorry, the code you entered will not be valid until: '.$r['valid_from'].'.';
					elseif ($r['valid_to']< $time_now && $r['valid_to']>$min_date) 
						$errors['unique_code']='Sorry, the code you entered is expired.';					
				}
			}
			else
				$errors['unique_code']='Sorry, the code you entered is not a valid code';			
	  }
	  $issue_code_available = false;
	  if(count($errors)==0)
	  {
		if($already_entered)
		{
			$title = $rowqr['Title'];
			$lname = $rowqr['Last_name'];
			$fname = $rowqr['First_Name'];
			$email = $rowqr['Email_Address'];
			
			$uid = $rowqr['Id'];
			$unique_code=$unique_code_on_record;
		}
		else
		{
			if($ucode_req!=1 && $ucode_req!=2)
				$unique_code=get_rand_id(12);
			
			$result="index_success.php";
			$assign_code = NULL;
			if($acode_req==1)
			{
				//mysql_query("update  set user_id=$uid,use_date=now(),Code='X$Code' where Code=$Code limit 1");
				//$sql = "select * from $assign_code_table  where user_id='' and code='$Code' limit 1";
				$db->from($assign_code_table);
				$db->where(array('user_id'=>'','code'=>$Code));

				if($ucode_req==2 )
				{
					$ucode_split = explode('-',$unique_code);
					$db->open_where();
					$db->where(array('location_id'=> 0));
					$db->or_where(array('location_id'=>$ucode_split[2]));
					$db->close_where();	
				}
				
				// SELECT add_message, uniquevalue from assigned_code_table WHERE user_id=0 AND Code=$code AND (location_id=0 OR location_id=$booking_id[2])
				$code_row=$db->fetch_first();
				//var_dump($db);
				//$result = mysql_query($sql) or die(mysql_error().$sql);
				if ($db->affected_rows > 0)
				{
					//$code_row = mysql_fetch_assoc($result);
					$assign_code = $code_row['uniquevalue'];
					$add_message = $code_row['add_message'];
				}
			}
			if( $acode_req ==0  || $assign_code!=NULL)
			{	
			
				if(empty($contact_by_email)) $contact_by_email = '4';
				if(empty($contact_by_sms)) $contact_by_sms = '4';
				if(empty($contact_by_us)) $contact_by_us = '4';
				if(empty($contact_by_other)) $contact_by_other = '4';

				$date = date('Y-m-d'); //to insert current date into table
				
				if($title == "Other") $title="";
				$ip=$_SERVER['REMOTE_ADDR'];

				if($_POST['partner1']==3 && $optin_live==1)
					$partner1=3;
				else
					$partner1=4;

				if($_POST['partner2']==4 )
					$partner2=4;
				else
					$partner2=3;
				
				$comp_data = array();
				$comp_data['Title']=$title; 
				$comp_data['First_Name']=$fname;
				$comp_data['Last_name']=$lname;
				$comp_data['Address_1']="$house_number $street";
				$comp_data['Address_2']=$address1;
				$comp_data['Address_3']=$address2;
				$comp_data['Town']=$city;
				$comp_data['Postcode']=$post_code;
				$comp_data['Day_Phone']=$telephone;
				$comp_data['Mobile_Phone']=$mobile;
				$comp_data['Email_Address']=$email;
				$comp_data['Date_Of_Birth']=$dob;
				$comp_data['Gender']=$gender;
				$comp_data['NI_Email_Permission']=$contact_by_email;
				$comp_data['NI_SMS_Permission']=$contact_by_sms;
				$comp_data['NI_Post_and_Phone_Permission']=$contact_by_us;
				$comp_data['Third_Party_Post_and_Phone_Permission']=$contact_by_other;
				$comp_data['Recency_Date']='now()';
				$comp_data['Partner_Permission']=$partner1;
				$comp_data['Answer']=$answer1;
				$comp_data['Answer2']=$answer2;
				$comp_data['Tickets']=$default_tickets;
				$comp_data['Booking_Id']=$unique_code;
				
				$comp_data['IP_Address']=$ip;
				$comp_data['OS']='';
				$comp_data['Registration_Date']='now()';
				$comp_data['Code']=$Code;
				
				$comp_data['Custom1']=$answer3;
				$comp_data['Custom2']=$age;
				$comp_data['Custom3']=$assign_code;
				 
				$uid = $db->insert($table, $comp_data);

				if($uid)
				{	
					//$update_sql= "update $unique_code_table set user_id=$uid,use_date=now() where id=$code_id limit 1";
					if($ucode_req==1 || $ucode_req==2)
					{	
						//$db->query($update_sql)->execute();
						$update_data = array();
						$update_data['user_id']=$uid;
						$update_data['use_date']='now()';
						$db->where(array('id'=>$code_id));
						$db->limit(1);
						$db->update($unique_code_table,$update_data);
					}

						
					// wait 5 mins to change
					//sleep(2);
					if($acode_req==1)
					{	
						$code_updated = update_codes($uid,$assign_code);
						if($code_updated)
							$issue_code_available = true;
					}
					else
						$issue_code_available = true;
				}
				else
					$errors['system']="System error : can not complete request.";
			}
			else
				$errors['full']='Sorry, all available items have now been claimed.';
		}
		//mysql_close();
		unset($_SESSION['user_token']);
		
		if($already_entered || $issue_code_available)
		{
			$dir = dirname(__FILE__);
			$dir = explode('/', $dir);
			$sha_salt = end($dir);
			$cookie_dir= '/'.end($dir).'-ls';
			$expiry_time = strtotime( '+1 day' );

			$_SESSION['title']=stripslashes($title);
			$_SESSION['lname']=stripslashes($lname);
			$_SESSION['fname']= stripslashes($fname);
			$_SESSION['email']= stripslashes($email);
			$_SESSION['rec_id']= stripslashes($uid);
			$_SESSION['code']= stripslashes($Code);
			$_SESSION['unique_code']= strtolower(stripslashes($unique_code));
//			$_SESSION['checksum']= sha1($lname.$fname.$sha_salt.$uid.$unique_code.$email);
			$_SESSION['checksum']= sha1($lname.$fname.$sha_salt.$uid.$_SESSION['unique_code'].$email.$Code);
			if(!empty($add_message))
				$_SESSION['add_message']= stripslashes($add_message);
			// set cookies too
/*	THIS WAS TO FIX SESSION ISSUE		
			setcookie("title", stripslashes($title),$expiry_time,$cookie_dir);
			setcookie("lname", stripslashes($lname),$expiry_time,$cookie_dir);
			setcookie("fname", stripslashes($fname),$expiry_time,$cookie_dir);
			setcookie("email", stripslashes($email),$expiry_time,$cookie_dir);
			setcookie("unique_code", strtolower(stripslashes($unique_code)),$expiry_time,$cookie_dir);
			setcookie("rec_id", stripslashes($uid),$expiry_time,$cookie_dir);
			setcookie("code", stripslashes($Code),$expiry_time,$cookie_dir);	
			setcookie("checksum", sha1($lname.$fname.$sha_salt.$uid.strtolower(stripslashes($unique_code)).$email.$Code),$expiry_time,$cookie_dir);
*/			
			$anchor = '';
			if($require_upload==1)
				$anchor = '#C_UPLOAD';
			header('Location:index_success.php'.$anchor);
			exit();
		}
	  }
	 }

 }
 else
 {
	$stime = explode(" ",$stime);
	$stime2 = explode('-',$stime[0]);

	$stime[0] = "$stime2[2]/$stime2[1]/$stime2[0] ";

	$etime = explode(" ",$etime);
	$etime2 = explode('-',$etime[0]);
	$etime[0] = "$etime2[2]/$etime2[1]/$etime2[0] ";
 }
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<?php
// please make sure $google_analytics is defined before below include
include(ROOT . 'common/ganalytics.php'); ?>

<title>
<?=$page_title;?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="ROBOTS" CONTENT="NOARCHIVE">
<meta name="description" content="">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Responsive and mobile friendly stuff -->
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, target-densitydpi=160dpi, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<?php 
// if $cdnurl is defined before following include the, All JS and CSS will be taken from CDN, local otherwise
include('include_jscss.php') ;?>
<style>
sup {
    vertical-align: super;
    font-size: smaller;
}
</style>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
	<div class="wraper">
		<? require('header.php');
	   	if($time_now < $stime || $time_now > $etime)
	   {
	  ?>
	  	<div class="contentpart">
	  		<br><br><br>
	  		This promotion opens on <?=$stime[0];?> at <?=$stime[1];?> and closes on <?=$etime[0] ;?> at <?= $etime[1];?>
	  		<br><br><br>
	  	</div>
	  <? }
	  else
	  {
	  ?>
		<!-- form start -->
		<form action="index.php" method="post" name="frm1" id="frm1">
			<div class="contentpart"> <!-- contentpart start -->
				<?
				if(count($errors) > 0 && $caller=='Submit')
				{
					echo '<div class="error" id="err_div"><ul>';
					foreach($errors as $error)
						echo "<li>$error</li>";
					echo '</ul></div>';

				}
				?>
				<div class="leftform"> <!-- leftform starts -->
					 <div class="formrow threcolom-first">
					<?php
		      if($question1=="" && $question2 ==""){

		      }
					if(!empty($question1)) {
	       ?>
				<!-- formrow start -->
					 <div class="twopart-form"> <!-- twopart-form start -->
						 <p><?=$question1; ?> <strong><span class="red">*</span> </strong></p>
						 <?php
						 $a_count=0;
						 if(!empty($answer1f)){
							 $answer1f = explode('|',$answer1f);
							 foreach($answer1f as $ans1)
							 {
									 $a_count++;
									 ?>
									 <div>
									 	<label class="control control--checkbox">
											 <input type="radio" <?php if($ans1==$answer1) echo 'checked';?> name="answer1" id="answer1<?php echo $a_count;?>" value="<?php echo $ans1;?>" >
											 <div class="control__indicator"></div>
											 <?php echo $ans1;?>
									 	</label>
								 	</div>
									<?php
							 	}
						 	}
						 	else {
								?>
						 		<textarea name="answer1" placeholder="Please enter your answer *" rows="4" class="inputbox pt-0 md-textarea" id="answer1"><?=stripslashes(htmlspecialchars($answer1));?></textarea>
						 		<?php } ?>
					 </div> <!-- twopart-form  end -->

				 <?php
			 }
			 ?>

				<?php
			 	if(!empty($question2)) {
        ?>
				<div class="twopart-form mobile-space">
				 <p><?=$question2; ?> <strong><span class="red">*</span> </strong></p> 
			<?php
			$a_count=0;
			if(!empty($answer2f)){
				$answer2f = explode('|',$answer2f);
				foreach($answer2f as $ans2)
				{
						$a_count++;
						?>
						<div class="filedbox">
						<label class="control control--checkbox">
							<input type="radio" <?php if($ans2==$answer2) echo 'checked';?> name="answer2" id="answer2<?php echo $a_count;?>" value="<?php echo $ans2;?>" />
											 <div class="control__indicator"></div>
							<?php echo $ans2;?>
						</label>
					</div>

						<?php
				}
			}

			else {
			 ?>
			<!-- <textarea name="answer2" placeholder="Please enter your answer *" rows="4" class="inputbox form-control  pt-0" id="answer2"><?=stripslashes(htmlspecialchars($answer2));?></textarea> -->
			<textarea name="answer2" rows="4" class="inputbox form-control md-textarea pt-0" id="answer2"><?=stripslashes(htmlspecialchars($answer2));?></textarea>
			<label><?=$question2; ?> <span class="red">*</span></label>
			<?php } ?>
 


		    <?php } 
					?>
			 </div> <!-- formrow end -->
			<?php 
						 if(!empty($question3)) {
	       ?>
				<!-- formrow start -->
					 <div class="twopart-form md-form question-3"> <!-- twopart-form start -->
						 <!-- <p><?=$question3; ?> <strong><span class="red">*</span> </strong></p> -->
						 <?php
						 $a_count=0;
						 if(!empty($answerset3)){
							 $answerset3 = explode('|',$answerset3);
							 foreach($answerset3 as $ans3)
							 {
									 $a_count++;
									 ?>
									 <div>
									 	<label class="control control--checkbox">
											 <input type="radio" <?php if($ans1==$answer3) echo 'checked';?> name="answer3" id="answer3<?php echo $a_count;?>" value="<?php echo $ans3;?>" >
											 <div class="control__indicator"></div>
											 <?php echo $ans3;?>
									 	</label>
								 	</div>
									<?php
							 	}
						 	}
						 	else {
								?>
								 <!-- <textarea name="answer3" placeholder="Please enter your answer *" rows="4" class="inputbox form-control pt-0" id="answer3"><?=stripslashes(htmlspecialchars($answer3));?></textarea> -->
								 <textarea name="answer3" rows="4" class="inputbox form-control md-textarea pt-0" id="answer3"><?=stripslashes(htmlspecialchars($answer3));?></textarea>
								 <label><?=$question3; ?> <strong><span class="red">*</span></label>
						 		<?php } ?>
					 </div> <!-- twopart-form  end -->
				 <?php
			 		}
					?>
             </div>
			 <?php if($ucode_req==1 || $ucode_req==2)
			 { ?>
				 <p><strong>Please enter your unique code</strong></p>
				 <div class="formrow md-form mb-0">
						<!-- <label class="fomrlabel">Unique Code <span class="red">*</span></label> -->
						<div class="filedbox">
							<input type="text" value="<?=stripslashes(htmlspecialchars($unique_code));?>" name="unique_code" class="inputbox form-control uniquecode-box">
							<label class="uniquecode-box">Unique Code <span class="red">*</span></label>
						</div>
					</div>
			 <?php } ?>
			 <!-- Other sections begins form here -->
			   <p class="pt-20"><strong>Please enter your details here and then click submit</strong></p>
				 	  <? if(0){ ?>  <!-- if condition starts -->
						<div class="formrow"> <!-- formrow start -->
							<div class="twopart-form">
													<!-- <label class="fomrlabel labletwoline">Please confirm age of the child you are nominating <span class="red">*</span></label> -->
													<div class="filedbox">
														<select name=age>
																<option value='' <?php if(empty($caller) || $age== '') echo 'selected'; ?>>---</option>
																<?php for($a=7;$a<14;$a++){ ?>
															<option value='<?php echo $a;?>' <?php if($age == $a) echo 'selected'; ?>><?php echo $a;?> Years</option>
																<?php } ?>
														</select>
													</div>
											</div>
											<div class="twopart-form">
												 <!-- <label class="fomrlabel">Title <span class="red">*</span></label> -->
													<div class="filedbox">
														<select name=title>
															<option value='' <?php if(empty($caller) || $title == '') echo 'selected'; ?>>---</option>
															<option value='Mr'<?php if($title == 'Mr') echo 'selected'; ?>>Mr</option>
															<option value='Mrs' <?php if($title == 'Mrs') echo 'selected'; ?>>Mrs</option>
															<option value='Miss' <?php if($title == 'Miss') echo 'selected'; ?>>Miss</option>
															<option value='Ms' <?php if($title == 'Ms') echo 'selected'; ?>>Ms</option>
															<option value='Other' <?php if($title == 'Other') echo 'selected'; ?>>Other</option>
														</select>
													</div>
											</div>
				 		</div> <!-- formrow end -->

					<?php } ?> <!-- if condition end -->
					<div class="formrow"> <!-- formrow start-->
	        	<div class="twopart-form">
	              <!-- <label class="fomrlabel">First Name <span class="red">*</span></label> -->
	              <div class="filedbox md-form mb-0">
					<input type="text" value="<?=stripslashes(htmlspecialchars($fname));?>" name="fname" class="inputbox form-control ">
					<label class="">First Name <span class="red">*</span></label>
	              </div>
	          	</div>
	            <div class="twopart-form">
	          		<!-- <label class="fomrlabel">Last Name <span class="red">*</span></label> -->
	                  <div class="filedbox md-form mb-0">
						<input type="text" value="<?=stripslashes(htmlspecialchars($lname));?>" name="lname" class="inputbox form-control">
						<label class="fomrlabel">Last Name <span class="red">*</span></label>
	                  </div>
	            </div>
	        </div> <!-- formrow end -->

					<div class="formrow"> <!-- formrow start -->
	        	<div class="twopart-form">
	              <!-- <label class="fomrlabel">Email <span class="red">*</span></label> -->
	              <div class="filedbox md-form mb-0">
					<input type="text" value="<?=stripslashes(htmlspecialchars($email));?>" name="email" class="inputbox form-control">
					<label class="fomrlabel">Email <span class="red">*</span></label>
	              </div>
	          	</div>
	            <div class="twopart-form">
	              <!-- <label class="fomrlabel">Confirm Email <span class="red">*</span></label> -->
	              <div class="filedbox md-form mb-0">
					<input type="text" value="<?=stripslashes(htmlspecialchars($confirm_email));?>" name="confirm_email" class="inputbox form-control">
					<label class="fomrlabel">Confirm Email <span class="red">*</span></label>
	              </div>
	          	</div>
	        </div> <!-- formrow end -->
					<?php if($show_dob==1) {?>
						 
					  <div class="formrow"> <!-- formrow start -->
						<div class="twopart-form">
								<!-- <label class="fomrlabel">House name/number <span class="red">*</span></label> -->
								<div class="filedbox md-form mb-0">
									<input type="date" min="1901-01-01" max="<?php echo date('Y-m-d');?>" value="<?=stripslashes(htmlspecialchars($dob));?>" name="date_of_birth" class="inputbox form-control">
									<label class="fomrlabel">Date of Birth <span class="red">*</span></label>
								</div>
							</div>
					</div> <!-- formrow end -->
					  <?php }
		  				if($show_address){ ?><!-- Address logic starts -->
							<div class="formrow"> <!-- formrow start -->
								<div class="twopart-form">
										<!-- <label class="fomrlabel">House name/number <span class="red">*</span></label> -->
										<div class="filedbox md-form mb-0">
											<input type="text" value="<?=stripslashes(htmlspecialchars($house_number));?>" name="house_number" class="inputbox form-control">
											<label class="fomrlabel">House name/number <span class="red">*</span></label>
										</div>
									</div>
									<div class="twopart-form">
										<!-- <label class="fomrlabel">Street <span class="red">*</span></label> -->
										<div class="filedbox md-form mb-0">
											<input type="text" value="<?=stripslashes(htmlspecialchars($street));?>" name="street" class="inputbox form-control">
											<label class="fomrlabel">Street <span class="red">*</span></label>
										</div>
									</div>
							</div> <!-- formrow end -->
							<div class="formrow"> <!-- formrow start -->
					        	<div class="twopart-form">
					                <!-- <label class="fomrlabel">Address 2 <span class="red"></span></label> -->
					                <div class="filedbox md-form mb-0">
										<input type="text" value="<?=stripslashes(htmlspecialchars($address1));?>" name="address1" class="inputbox form-control">
										<label class="fomrlabel">Address 2 <span class="red"></span></label>
					                </div>
					            </div>
					            <div class="twopart-form">
					              <!-- <label class="fomrlabel">City <span class="red">*</span></label> -->
					              <div class="filedbox md-form mb-0">
									<input type="text" value="<?=stripslashes(htmlspecialchars($city));?>" name="city" class="inputbox form-control">
									<label class="fomrlabel">City <span class="red">*</span></label>
					              </div>
					          	</div>
					        </div> <!-- formrow end -->
									<div class="formrow"> <!-- formrow start -->
					        	<div class="twopart-form">
					              <!-- <label class="fomrlabel">Post Code <span class="red">*</span></label> -->
					              <div class="filedbox md-form mb-0">
									<input type="text" value="<?=stripslashes(htmlspecialchars($post_code));?>" name="post_code" class="inputbox 
									form-control">
									<label class="fomrlabel">Post Code <span class="red">*</span></label>
					              </div>
					          	</div>
					        </div> <!-- formrow end -->

						<?php } if(0) {
							 // Address logic ends  and start of another logic
							 /*
										 <div class="formrow">
											 <label class="fomrlabel">Your Story <span class="red">*</span></label>
												 <div class="filedbox">
														 <textarea name="story" rows="5" class="story"><? //=stripslashes(htmlspecialchars($story));?>
														 </textarea>
														 Maximum 500 words (<span id="wcount">500</span> words left)
												 </div>
										 </div>
						*/
					}
					if($show_phone)
					{
					?>
					<!-- Telephone  -->
					<div class="formrow"> <!-- formrow start -->
						<div class="twopart-form">
							<!-- <label class="fomrlabel">Mobile Phone Number <span class="red">*</span></label> -->
							<div class="filedbox md-form mb-0">
								<input type="text" value="<?=stripslashes(htmlspecialchars($telephone));?>" name="telephone" class="inputbox form-control">
								<label class="fomrlabel">Mobile Phone Number <span class="red">*</span></label>
<div class="termcondition pt-0">Please note you must enter a valid <?php if (strtolower($page_css )== "o2" ) echo 'O2'; ?> Mobile phone number or your entry will be invalidated.</div>
							</div>
						</div>
					</div> <!-- formrow end -->
					<?php } ?>
						
                        <div class="righttextpart"> <!-- righttextpart start -->
							 <?
							 if($optin_live==1)
	 		 			 {
	 		 				?>
	 		 			 <div>
	 						 <div class="termcondition">
	 		 						<div class="withcheckbox">
	 		 							<label class="control control--checkbox tearmcheckbox">
	 		 								<input type="checkbox" value="3"  name="partner1" id="partner1"  <? if(@$_POST['partner1']==3) echo ' checked'; ?> >
	 		 								<div class="control__indicator"></div>
	 		 							</label>
	 		 		 <strong id="terms_link">&nbsp;Tick here&nbsp;<?=$optin;?></strong>
	 				 <br>
	 				 <span id="terms_link">
	 		 			 Your information will be used in accordance with the <?=$optin;?> <a href="<?=$brand_pplink;?>" target="_blank">Privacy Policy</a>.</span><br><br>
					 </div>
				 </div>
			 </div>

	 		 				 <? }
	 		 	 ?>

			 </div>
             <div class="righttextpart"> <!-- righttextpart start -->
			         <? if(0) {
			 				 /*
			             	<p><strong>Subscription and Reading Questions</strong></p>
			                  <div class="formrow">
			                 	<label class="fomrlabel">Reader Name <span class="red">*</span></label>
			                     <div class="filedbox">
			                     	<input type="text" value="<?=$reader;?>" name="reader" class="inputbox">
			                     </div>
			                 </div>

			                  <div class="formrow">
			                 	<label class="fomrlabel">Paper/Subscription <span class="red">*</span></label>
			                     <div class="filedbox"><select name=subscription>
			                            <option value='' >---</option>
			                            <option value='Weekly' >Times weekly</option>
			                            <option value='Online' >Times+ online</option>
			                            <option value='Bundle' >Times bundle</option>
			                            <option value='Paper' >Paper only</option>
			                            <option value='Other' >Other</option>
			                            </select>
			                     </div>
			                 </div>
			                  <div class="formrow">
			                 	<label class="fomrlabel">Frequency <span class="red">*</span></label>
			                     <div class="filedbox">

			                     	<input type="text" value="<?=$telephone;?>" name="telephone" class="inputbox">
			                     </div>
			                 </div>
			                  <div class="formrow">
			                 	<label class="fomrlabel">On which day you buy? <span class="red">*</span></label>
			                     <div class="filedbox">
			                    	  <input type="checkbox" name="buyday" value="Mon"> Monday
			                         <input type="checkbox" name="buyday" value="TUE"> Tuesday
			                         <input type="checkbox" name="buyday" value="WED"> Wednesday
			                         <input type="checkbox" name="buyday" value="THU"> Thursday
			                         <input type="checkbox" name="buyday" value="FRI"> Friday
			                         <input type="checkbox" name="buyday" value="weekends"> Weekends

			                     </div>
			                 </div>
			                 <div class="formrow">
			                 	<label class="fomrlabel">How many time you buy in a month? <span class="red">*</span></label>
			                     <div class="filedbox">
			                    	  <input type="radio" name="buyday" value="once"> Once a month
			                         <input type="radio" name="buyday" value="23time"> 2-3 time
			                         <input type="radio" name="buyday" value="sunday-only"> Every sunday
			                     </div>
			                 </div>
			                  */
			 				 } ?>
							 <div>
			         	<div class="termcondition">
			         		<div class="withcheckbox">
			             	<label class="control control--checkbox tearmcheckbox">
			                     <input type="checkbox" name="terms_agreed" value="agree">
			                     <div class="control__indicator"></div>
			             	</label>
			              	<a href="javascript:void(0);" id="terms_link" class="textarrow-up" onclick="toggletc();">Click here to accept our terms and conditions</a>
			           </div>
			          <?=$terms;?>
			 				</div>
			       </div>
						 </div>
						<!-- end logic -->
						 </div> <!-- leftform end -->

						  <!-- righttextpart end -->

						<!-- righttextpart end -->
						   <div class="clear"><br></div>
							 <? /// COA full div
										/*
								<div>
											 <p><strong>Condition of Entry</strong></p>
											 <p>By entering the promotion, you agree to receive information on offers and promotions from Times Newspapers Limited, publishers of The Times and The Sunday Times, but you'll be able to opt out at any time. Your information will be used in accordance with our <a target="_blank" href="http://www.newsprivacy.co.uk/single/">Privacy Policy.</a></p>
											 <p>We'll pass your details to Carluccio`s for them to send you promotions, offers and information that may be of interest to you unless you
												 <label>tick here
													 <input type="checkbox" value="4" name="partner1" id="partner1"  <? if($_POST['partner1']==4) echo ' checked'; ?> >
												 </label>
											 </p>
											 <p>Your information will be used in accordance with the Carluccio`s&nbsp;<a target="_blank" href="http://www.carluccios.com/privacy">Privacy Policy</a>.</p>
											 <div class="termcondition">
												 <?=$terms;?>

											 </div>
									 </div>

									 */

									 if($verify_captcha){   // captcha starts
										 ?>
										 <div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_KEY;?>"></div>
									 <?php } // captcha end
									 ?>
									 <div class="buttondiv">
										 <?
									//	$user_token = '1111111';
									$user_token = get_rand_id(10);
									$_SESSION['user_token']=$user_token;?>

										 <input type="hidden" name="user_token" id="user_token" value="<?=$user_token;?>">
										 <input type="submit" value="Submit" name="caller" class="btn_big">
										 <input type="Reset" value="Reset" class="btn_big">
									 </div>

			</div>
			<!-- contentpart end -->
		</form>
		<!-- form end -->


		<?php
		}
	?>
	</div>



<!--<div class="thisisafooter">
  <div class="container">
    <div class="footercolumn">
      <div class="footercolumntitle"> </div>
      <a href="mailto:admin@media-promotions.com"></a></div>
    <div class="footercolumn">
      <div class="footercolumntitle"></div>
    </div>
  </div>
</div>
<div class="copyrights">
  <div class="container"> Website &copy; Media Promotions (Digital) Ltd<br>
    62-70 Shorts Gardens, London WC2H 9AH. <br>
    Registered in England No. 10160515. </div>
</div>-->

<footer class="footer-distributed">
  <div class="footer-left"> <img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/wtilogo3.png" width="100" >
    <p class="footer-links2"> <a href="#actionjava" class="toggleDiv" target_div="div_about">About</a></p>
    <div class="tearms-togletaxt" id="div_about">
      <p>WeTicketIt® is a full service ticketing platform under licence to See Film First Ltd, designed for customer loyalty, media promotions, membership and rewards programmes. We originally ticketed for film promotions and premieres in the early 2000s, but rapidly grew into broader entertainment promotions. We provide the technical and logistical support for leading rewards programmes in the UK, USA, Europe and Australia. See Film First Ltd is an audience provider. We fulfil targeted audience requirements for 'word of mouth' and test screenings, shows and events.</p>
    </div>
  <p class="footer-links2"> <a href="#actionjava" class="toggleDiv" target_div="div_privacy">Privacy policy</a></p>
    <div class="tearms-togletaxt" id="div_privacy"><p>At See Film First Ltd, we are committed to maintaining the trust and confidence of visitors to our web sites. In particular, we want you to know that See Film First Ltd is not in the business of selling, renting or trading email lists with other companies and businesses. But just in case you don&rsquo;t believe us, in this Privacy Policy, we&rsquo;ve provided lots of detailed information on when and why we collect your personal information, how we use it, the specific conditions under which we may disclose it to others and how we keep it secure. Grab a cuppa and read on.</p>
<p><br></p>
<p>This privacy policy applies to the websites (&#39;Sites&#39;) operated by the software / website licensor, See Film First Ltd and to the processing of personal information as contemplated in this privacy policy. Any reference to &#39;you&#39; or &#39;your&#39; means you, the user. See Film First respects your privacy and is committed to protecting the personal information you share with us. This statement describes how we collect and use your personal information that we may collect about you through the Sites and from our communications with you. The Sites are hosted on various servers within the European Economic Area.</p>
<p><br></p>
<p>Data Protection Laws</p>
<p>We are conscious of our legal responsibilities as a &#39;data controller&#39; and we support the principles of data protection reflected in The General Data Protection Regulation (GDPR) (EU) 2016/679 and applicable national implementing legislation. We shall endeavour to ensure that the personal information we obtain and use will always be held, used, transferred and otherwise processed in accordance with our legal obligations.</p>
<p><br></p>
<p>Other websites</p>
<p>There may be links from the Sites to other websites. This privacy policy only applies to the Sites and not to any other websites including any websites linked from any of the Sites. Accessing those third party websites or sources requires you to leave the Sites. We do not control those third party websites or any of the content they contain and you expressly acknowledge and agree that we are in no way responsible or liable for any of those third party websites, including, without limitation, the content, policies, failures, promotions, products, services or actions of those websites and/or any damages, losses, failures or problems caused by, related to or arising from those websites. We encourage you to review all policies, rules, terms and regulations, including the privacy policies, of each website that you visit.</p>
<p><br></p>
<p>How we obtain personal information about you</p>
<p>We may as a result of your interaction with our Sites and our communications with you hold and process personal information obtained about you. The types of personal information we collect may include:</p>
<p>your name</p>
<p>your phone number</p>
<p>your email address</p>
<p>your address</p>
<p>your profession</p>
<p>your date of birth</p>
<p>your entertainment preferences</p>
<p>your customer priority number</p>
<p>From time to time, we may alter the specific types of personal information we collect.</p>
<p><br></p>
<p>How we collect and use non-personal information</p>
<p>Please note that when anyone visits any of the Sites, we automatically collect certain non-personal information such as the type of computer operating system (e.g., Windows or Mac OS) and browser (e.g., Safari, Chrome, Internet Explorer) being used, and the domain name of the internet service provider. We often collect information on your entertainment preferences. We sometimes use the non-personal information that we collect to improve the design and content of the Sites and to enable us to personalise your experience.</p>
<p><br></p>
<p>What we do with your personal information</p>
<p>The personal information that you provide will be available to See Film First, our affiliated companies and trusted third party service providers and contractors for the following purposes:</p>
<p>to process enquiries received via the Sites</p>
<p>to register you for and send you newsletters</p>
<p>to conduct market research and business development</p>
<p>to provide you with information about products and promotions that may be of interest to you from ourselvesin connection with surveys related to our products and the Sites any other purpose indicated at the time you voluntarily provide your personal information</p>
<p><br></p>
<p>Payment Processing</p>
<p>In any instance where we process payments through the Websites, the payment information and the processing function is handled by STRIPE in accordance with their data protection and privacy policies. We do not receive, process or store credit or debit card details, bank details or payment information of any kind, nor do we share customer details with any 3rd parties.</p>
<p><br></p>
<p>Marketing</p>
<p>We may from time to time use your personal information to send you automated email messages or marketing materials regarding our services and the services of third party suppliers, in each case with your prior consent. These email messages may contain features that help us make sure you received and were able to open the message. You may opt out of receiving such marketing email messages at any time, free of charge, by replying to the message with &#39;unsubscribe&#39; in the subject line, or by following the instructions in any marketing communication.</p>
<p><br></p>
<p>To make our website better</p>
<p>We will also process your personal data in order to provide you with a more tailored experience when you visit our website. For example, we may use your personal data to tailor our website, make sure it is displayed in the most effective way for the device you are using. We also use various third party cookies to help us improve our website (more details are set out in the sections below) but we will not share your personal data with the third party analytics and search engine providers that assist us in the improvement and optimisation of our website (see cookies and tracking software below). We will also process your personal data for the purposes of making our website more secure, and to administer our website and for internal operations, including troubleshooting, data analysis, testing, research and statistical and survey purposes. The legal basis on which we process your personal data in these circumstances is our legitimate interest to provide you with the best possible website we can, and to ensure that our website is kept secure.</p>
<p><br></p>
<p>Cookies and tracking software</p>
<p>We use &#39;cookies&#39; and other types of tracking software in order to personalise your visit to our Sites and enhance your experience by gaining a better understanding of your particular interests and customising our pages for you. A cookie is a message given to a web browser by a web server and which is then stored by the browser in a text file. Each time the browser requests a page from the server this message is sent back which enables the user&#39;s computer to be identified.</p>
<p>We may use the information provided by cookies to analyse trends, administer the Sites, or for research and marketing purposes to help us better serve you..</p>
<p>In specific we use:</p>
<p>Session and Browser Cookies - to keep you logged in and to prevent malicious attacks. These are accessed by our software to automatically revalidate users during the booking process. Google Analytics Cookies - to measure traffic, user experience, website performance and other statistical information provided by Google Analytics. These are accessed by Google and the results provided to us in the form of anonymised graphs, charts and raw data.</p>
<p>
</p>
<p>No information which in itself personally identifies you will be collected through the cookies. These cookies are not used for any purpose other than those described here. If you like, you can set your browser to notify you before you receive a cookie so you have the chance to accept it and you can also set your browser to turn off all cookies. The website www.allaboutcookies.org (run by the Interactive Marketing Bureau) contains step-by-step guidance on how cookies can be switched off by users.</p>
<p>Please note that our Sites require the use of cookies in order to work at their best in some instances to work at all. If you do not wish these cookies to be used then please note that you may not experience our Sites working to their best effect or at all.</p>
<p><br></p>
<p>Disclosing your personal information</p>
<p>We may disclose your personal information to companies and individuals who perform business functions and services on our behalf. Such functions may include hosting the Sites, analysing data and providing other support services. All such parties will be required to keep your personal data secure and only process it in accordance with our instructions. We may also disclose your personal information if, in our opinion, disclosure is required by law.</p>
<p>Finally, we may disclose your personal information if you have explicitly given consent for us to do so, and then only to the third party identified at the time of consenting - and in accordance with their own privacy policy.</p>
<p><br></p>
<p>International transfers of your personal information</p>
<p>We may transfer and process any personal information you provide to us to countries outside the European Economic Area whose laws may not afford the same level of protection to your personal information. We will therefore ensure that all adequate safeguards are in place and that all applicable laws and regulations are complied with in connection with such transfer.</p>
<p><br></p>
<p>The accuracy of your information</p>
<p>While we endeavour to ensure that the information we hold about you is accurate and kept up to date, we shall assume that in the absence of evidence to the contrary, the information you provide us with is accurate. Should you inform us of inaccuracies in the information which we hold in relation to you or, if we discover that such information is inaccurate, it shall be promptly rectified by us. We do not intentionally retain any information about you which is out of date or which is no longer required. Personal Data Security and Confidentiality</p>
<p>We maintain adequate technical and organisational security measures to protect your personal information from loss, misuse, and unauthorised access, disclosure, alteration, or destruction.</p>
<p><br></p>
<p>Access and Correction Rights</p>
<p>By logging in to our ticketing sites, you can see the profile information we hold on you and you can update it there. You can also see your booking history.</p>
<p>You can request access to, and have the opportunity to update and amend your personal information, and you can exercise any other rights you enjoy under applicable data protection laws, including, for example, objection to and blocking of the processing of your personal information, by contacting us at support@weticketit.com. For security reasons, we reserve the right to take steps to authenticate your identity before providing access to your personal information. Subject to applicable law, we may charge you a small fee to access your data.</p>
<p><br></p>
<p>Retention of data</p>
<p>We will keep your personal information for as long as necessary for the purposes for which it was collected, to provide you with services and to conduct our legitimate business interests or where otherwise required by law.</p>
<p>In instances where our rules are broken by a user and we ban the user from further accessing our sites, we will retain ad infinitum such information as is necessary to identify the user in order to prevent access.</p>
<p>Changes to the Policy</p>
<p>We reserve the right, at our sole discretion, to modify, add or remove sections of this privacy policy at any time and any changes will be notified to you using the email address you have given us or by an announcement on the webpage available at the &#39;Privacy Policy&#39; link on the Sites. Your continued use of the Sites, following the posting of changes to this privacy policy, will mean you accept these changes.</p>
<p><br></p>
<p>Privacy Queries</p>
<p>If you have any questions regarding this policy, or you wish to update your details or remove your personal data from our records, please inform us by emailing support@weticketit.com.</p>
<p><br></p></div>
  <p class="footer-links2">
  <a href="#actionjava" class="toggleDiv" target_div="div_refunds">Refunds</a>
	</p>
	<div class="tearms-togletaxt" id="div_refunds"><p>Please refer to Section 8 (Refunds) and section 9 (Liability) of our Terms & Conditions below.</p></div>

	<p class="footer-links2"><a href="#actionjava" class="toggleDiv" target_div="div_terms2">Terms of service</a> </p>
  <div class="tearms-togletaxt" id="div_terms2"><p>See Film First Ltd is a company registered in England and Wales under company number 4594879, with registered office at 37/38 Margaret Street, London W1G 0JF (&ldquo;we&rdquo;, &ldquo;us&rdquo;, &ldquo;our&rdquo;). This Booking Policy is designed to ensure your satisfaction and understanding of the purchase process on weticketit.com.1. GENERAL &amp; DEFINITIONS</p>
<p>1.1 We &lsquo;Offer&rsquo; (sell or give away) tickets and associated products and/or services on behalf of partners, license holders, artists, agents, producers, promoters, record labels, teams and venues. We refer to these parties who organise or provide the event and/or from whom we obtain tickets and/or associated products or services to sell or offer for free to you as our &quot;Event Partner&quot;.</p>
<p>1.2 We Offer tickets as and when allocated by Event Partners. The quantity of tickets made available by us vary on an event by event basis. Tickets are generally offered through several distribution points, including online, call centres and, in some cases, box offices. Tickets for popular events may sell out quickly. Occasionally, additional tickets may be available prior to the event, however We do not always control this inventory or its availability.</p>
<p>1.3 For some events, tickets may be Offered as part of a &ldquo;Package&rdquo; (where a ticket for an event is Offered together with concessions, merchandise or other valuable benefits such as exclusive seating arrangements, accommodation, transport, dining or merchandise as an inclusive package at an inclusive price)</p>
<p>1.4 In this Booking Policy, we refer to any products and/or services Offered by us as &ldquo;Items&rdquo;.</p>
<p>1.5 By placing an &lsquo;Order&rsquo; (a request for an Item), if we are able to fulfil the order, we will confirm it on screen and in email, usually in the form of a ticket or booking confirmation. Once we have confirmed that order, whether a financial transaction is required or not, we refer to this completion as a &ldquo;Booking&rdquo;.</p>
<p>1.6 To Order Item(s) from us, you must be 18 or over and have a valid credit/debit card issued in your name. To book Item(s) from us which are free, you must be 13 or over unless otherwise specified.2. CONTRACT</p>
<p>2.1 Any booking through our platform whether free or paid is subject to: (i) this Booking Policy; (ii) any special terms and conditions which may be displayed on our website; and (iii) the terms and conditions of the Event Partner(s) and/or event, which can be found on their respective websites. Venue terms and conditions may also be available at the venue box office.</p>
<p>2.2 Your contract of Booking of an Item starts once we have confirmed your Order and ends immediately after the completion of the event for which you have Ordered the Item, save that, if you have Ordered any physical product, your contract for the Booking of such product will end 14 days after the date of delivery of the product to you. All Orders are subject to payment card verification and/or other security checks and your transaction may be cancelled if it has not passed our verification process.</p>
<p>2.3 You agree not to obtain or attempt to obtain any Items through unauthorised use of any robot, spider or other automated device or any other illegal or unauthorised activity. We reserve the right to cancel any transaction which we reasonably suspect to have been made in breach of these provisions without any notice to you and any and all Items purchased as part of such transaction will be void.</p>
<p>2.4 We reserve the right to cancel Bookings which we reasonably suspect to have been made fraudulently.3. PRICES AND FEES</p>
<p>3.1 Orders from us may be subject to a per Item service charge and a non-refundable per order delivery fee.</p>
<p>3.2 Whilst we try to ensure that all prices on our website are accurate, errors may occur. If we discover an error in the price of any Item you have Ordered, we will inform you as soon as possible and give you the option of reconfirming your Order at the correct price (and credit or debit your account as applicable) or cancelling your Order. If we are unable to contact you, you agree that we may treat the order as cancelled. If you choose to cancel after you have already paid the incorrect price, you will receive a full refund from us.4. CANCELLATIONS</p>
<p>4.1 If you have purchased a ticket, or a Package, you are not entitled to cancel your purchase.</p>
<p>4.2 If you have purchased any product, you are entitled to cancel your transaction within fourteen (14) days of the date of delivery of the product (or during such longer period as may be specified in the Event Partner&rsquo;s terms and conditions). You are not entitled to cancel any associated ticket purchase in such circumstances.</p>
<p>4.3 If you have Booked a free ticket, you may cancel the ticket via the website up until the cancellation deadline which will vary from event to event.5. DELIVERY</p>
<p>5.1 We aim to dispatch tickets as soon as possible. We aim to inform you of the timescales on your booking confirmation page and email. In some instances we are not able to specify the exact dates of dispatch, as the arrangements for dispatch depend on when we are in possession of the ticket stock used for a particular event. For some events, we receive ticket stock from our Event Partners close to the event date.</p>
<p>5.2 Please allow as much time as possible for your tickets to arrive. If your tickets have not arrived three days before the event (or, if you are travelling, three days before you leave on your journey), please contact us. Please include your reference number and the name and postcode the booking is made under.</p>
<p>5.3 We post tickets to the address given at point of Order. Please note that if the address in your booking does not correspond to that held by your credit card company, we may cancel your tickets.</p>
<p>5.4 We reserve the right to make tickets available for collection by you at the venue box office. We will notify you by telephone or email of the arrangements for collection (using the details provided by you at the time of ordering) if this becomes necessary. You may be required to provide your booking confirmation email and your photo ID to collect tickets.</p>
<p>5.5 Any and all products and/or services included in a Package are provided and fulfilled by our Event Partners, who are responsible for the delivery and the quality of such products and/or services. If you have any queries or complaints regarding the non-ticket element of the Package, please contact the relevant Event Partner directly. For contact details, please refer to the booking confirmation email.6. TICKETS</p>
<p>6.1 Any ticket you purchase from us remains the property of the Event Partner and is a personal revocable licence which may be withdrawn and admission refused at any time. If this occurs, you will be refunded the sale price of the ticket which has been withdrawn or for which access was refused (including the relevant per ticket service charge but excluding the per order handling fee).</p>
<p>6.2 Policies set by our Event Partners, may prohibit us from issuing replacement tickets for any lost, stolen, damaged or destroyed tickets. For example for non-seated events, allowing a possibility of both the original and replacement tickets being used, may compromise the licensed capacity of the venue. If replacement tickets are being issued, we may charge you a reasonable administration fee.</p>
<p>6.3 When you receive your tickets, please keep them in a safe place. We will not be responsible for any tickets that are lost or stolen. Please note that direct sunlight or heat can sometimes damage tickets.</p>
<p>6.4 It is your responsibility to check your tickets; mistakes cannot always be rectified.</p>
<p>6.5 You have a right only to a seat of a value corresponding to that stated on your ticket. We, the venue or Event Partner reserve the right to provide alternative seats (whether before or during the event) to those initially allocated to you or specified on the tickets.RESTRICTIONS</p>
<p>6.6 When Ordering tickets from us, you are limited to a specified number of tickets for each event. This number is included on event information page and is verified with every transaction. This policy is in effect to discourage unfair ticket ordering. Tickets may be restricted to a maximum number per person, per credit card and, for some events, a restriction may apply per household. We reserve the right to cancel tickets booked in excess of this number without prior notice.</p>
<p>6.7 Tickets may be Offered subject to certain restrictions on entry or use, such as restricted, obstructed or side view or a minimum age for entry. Any such restriction shall be displayed on our website or otherwise notified to you before or at the time you book the tickets. It is your responsibility to ensure that you read all notifications displayed on our website.</p>
<p>6.8 You may not resell or transfer your tickets if prohibited by law. In addition, Event Partners may prohibit the resale or transfer of tickets for some events. Any resale or transfer (or attempted resale or transfer) of a ticket in breach of the applicable law or any restrictions imposed by the Event Partner is grounds for seizure or cancellation of that ticket without refund or other compensation.</p>
<p>6.9 You may not combine a Ticket with any hospitality, travel or accommodation service and/or any other merchandise, product or service to create a package, unless formal written permission is given by us and the Event Partner.</p>
<p>6.10 A ticket shall not be used for advertising, promotions, contests or sweepstakes, unless formal written permission is given by the Event Partner, provided that even if such consent is obtained, use of our trade marks and other intellectual property is subject to our prior consent.7. EVENT</p>
<p>7.1 It is your responsibility to ascertain whether an event has been cancelled and the date and time of any rearranged event. If an event is cancelled or rescheduled, we will use reasonable endeavours to notify you of the cancellation once we have received the relevant authorisation from the Event Partner. We do not guarantee that you will be informed of such cancellation before the date of the event.</p>
<p>7.2 Please note that advertised start times of events are subject to change.</p>
<p>7.3 Tickets are sold subject to the Event Partner&rsquo;s right to alter or vary the programme due to events or circumstances beyond its reasonable control without being obliged to refund monies or exchange tickets, unless such change is a material alteration as described in paragraph 8.4, in which case the provisions of this paragraph shall apply.<br>
  <br>
  8. REFUNDS</p>
<p>8.1 Occasionally, events are cancelled, rescheduled or materially altered by the team, performer or Event Partner for a variety of reasons. Contact us for exact instructions.</p>
<p>8.2 Cancellation: If an event is cancelled (and not rescheduled), you will be offered a refund of the sale price of your ticket(s), including the relevant per ticket service charge but excluding the per order handling fee. If an event takes place over several days and one or more day(s) is/are cancelled (but not all the days constituting the event), a partial refund only may be payable corresponding to the day(s) cancelled. If your Booking involved no financial transaction, no compensation financial or otherwise will be offered.</p>
<p>8.3 Rescheduling: Unless indicated otherwise in relation to a particular event, if an event is rescheduled, you will be offered seats at any rescheduled event (subject to availability) of a value corresponding with your original tickets. If you are unable to attend the rescheduled event, you will be offered a refund of the sale price of your ticket(s) including the relevant per ticket service charge but excluding the per order handling fee. You must inform us within the time specified by us if you are unable to attend the rescheduled event, otherwise we may reconfirm your booking for the rescheduled date and you will not be entitled to claim a refund. If your Booking involved no financial transaction, we may transfer your tickets to the new date or we may offer you an exclusive rebooking window. No compensation financial or otherwise will be offered.</p>
<p>8.4 Material alteration: If an event is materially altered, you will be offered an option to either reconfirm your order for the altered event or to claim a refund (of the sale price of your ticket(s) including the relevant per ticket booking fee but excluding per order handling fee), within such time as specified by us. Failure to inform us of your decision may result in your order being reconfirmed for the altered event and you will not be entitled to claim a refund. A &lsquo;material alteration&rsquo; is a change which, in our and the Event Partner&rsquo;s reasonable opinion, makes the Event materially different to the Event that Bookers of tickets, taken generally, could reasonably expect. The use of understudies in theatre performances and/or any changes of: (i) any supporting act; (ii) members of a band; and/or (iii) the line-up of any multi-performer or multi-talent event (such as a festival or a TV recording or Q&amp;A that forms part of a screening event or exhibition) shall not be a material alteration. If your Booking involved no financial transaction, no compensation financial or otherwise will be offered.</p>
<p>8.5 To claim your refund, please apply in writing to: See Film First Ltd, 62-70 Shorts Gardens, London WC2H 9AH (or to such other address as may be notified to you by us). You must enclose your unused tickets and comply with any other reasonable instructions from us. For accounting purposes your unused tickets must be received within 28 days from the date of the cancelled event.</p>
<p>8.6 If you have Ordered from us any Item associated with an event which has been cancelled, rescheduled or materially altered (such as car parking or travel) and a refund of a ticket is due to you in accordance with this clause 8, we will also refund you the purchase price of such Item purchased from us, including the per Item service charge but excluding the per order handling charge. If your Booking involved no financial transaction, no compensation financial or otherwise will be offered.</p>
<p>8.7 This Booking Policy does not and shall not affect your statutory rights as a consumer. For further information about your statutory rights contact Citizens Advice or the Department for Business Innovation and Skills.</p>
<p>8.8 We regret that, unless paragraphs 8.2, 8.3 or 8.4 apply, tickets cannot be exchanged or refunded after purchase.<br>
9. LIABILITY</p>
<p>9.1 Personal arrangements including travel, accommodation or hospitality relating to the Event which have been arranged by you are at your own risk. Neither we nor the Event Partner(s) shall be liable to you for any loss of enjoyment or wasted expenditure.</p>
<p>9.2 Unless otherwise stated in this clause 9, our and the Event Partner(s)&rsquo; liability to you in connection with the event (including, but not limited to, for any cancellation, rescheduling or material change to the programme of the event) and the Item you have purchased shall be limited to the price paid by you for the Item, including any per item service charge but excluding any per order handling fee. If your Booking involved no financial transaction, no compensation financial or otherwise will be offered.</p>
<p>9.3 Neither We nor the Event Partner(s) will be liable for any loss, injury or damage to any person (including you) or property howsoever caused (including by us and/or by the Event Partner(s)): (a) in any circumstances where there is no breach of a legal duty of care owed by us or the Event Partner(s); (b) in circumstances where such loss or damage is not a reasonably foreseeable result of any such breach (save for death or personal injury resulting from our negligence); or (c) to the extent that any increase in any loss or damage results from breach by you of any of the terms of this Booking Policy and/or any terms and conditions of the Event Partner(s) or your negligence.</p>
<p>9.4 Nothing in this Booking Policy seeks to exclude or limit our or the Event Partner(s)&rsquo; liability for death or personal injury caused by our or the Event Partner(s)&rsquo; (as relevant) negligence, fraud or other type of liability which cannot by law be excluded or limited.10. ADMISSION AND ATTENDANCE</p>
<p>10.1 The venue reserves the right to refuse admission should patrons breach any terms and conditions of the event or Event Partner. The venue may on occasions have to conduct security searches to ensure the safety of the patrons.</p>
<p>10.2 Every effort to admit latecomers will be made at a suitable break in the event, but admission cannot always be guaranteed.</p>
<p>10.3 There will be no pass-outs or re-admissions of any kind.</p>
<p>10.4 The unauthorised use of photographic and recording equipment is prohibited. Any photos, videos and/or recordings may be destroyed or deleted. Laser pens, mobile phones, dogs (except guide dogs) and a patron&rsquo;s own food and drink may also be prohibited (please check with the venue).</p>
<p>10.5 You and other ticket holders consent to filming and sound recording as members of the audience.</p>
<p>10.6 Prolonged exposure to noise may damage your hearing.</p>
<p>10.7 Special effects which may include, without limitation, sound, audio visual, pyrotechnic effects or lighting effects may be featured at an event.11. QUERIES AND COMPLAINTS</p>
<p>11.1 If you have any queries or complaints regarding your purchase, contact us, quoting your order number given to you at the conclusion of placing the order.</p>
<p>11.2 Because we sell Items on behalf of Event Partners, we may need to contact them for more information before responding to your complaint. Some complaints can take up to 28 days to resolve, but we will get back to you as soon as possible.</p>
<p>11.3 If any dispute arises, we shall use our reasonable endeavours to consult or negotiate in good faith, and attempt to reach a just and equitable settlement satisfactory to you, us and the Event Partner.</p>
<p>11.4 Although this does not restrict your rights to pursue court proceedings, if we are unable to settle any dispute by negotiation within 28 days, you and we may attempt to settle it by mediation. To initiate a mediation a party must give written notice to the other parties to the dispute requesting a mediation.</p>
<p>11.5 As an online trader, pursuant to European Union legislation, we also draw your attention to the European Commission&rsquo;s Online Dispute Resolution platform here, where you can access further information about online dispute resolution.You can also email us at support@weticketit.com12. MISCELLANEOUS</p>
<p>12.1 The Event Partner and its affiliates, successors, or assigns may enforce these terms in accordance with the provisions of the Contracts (Rights of Third Parties) Act 1999 (the &ldquo;Act&rdquo;). Except as provided above, this agreement does not create any right enforceable by any person who is not a party to it under the Act, but does not affect any right or remedy that a third party has which exists or is available apart from that Act.</p>
<p>12.2 All of these terms and conditions are governed by English Law and any disputes arising out of any transaction with TicketWeb are subject to the exclusive jurisdiction of the English Courts.</p>
  </div>
    <p class="footer-company-name">Website & Software © 2016 Media Promotions (Digital) Ltd. Under licence to See Film First Ltd. </p>
  </div>
  <div class="footer-center">
    <div> <i class="fa"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/footer-location-icon.jpg" ></i>
      <p><span>62-70 Shorts Gardens</span> London WC2H 9AH</p>
    </div>
    <div> <i class="fa"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/footer-email-icon.jpg" ></i>
      <p><a href="mailto:support@weticketit.com">support@weticketit.com</a></p>
    </div>
  </div>
  <div class="footer-right">
    <div class="footer-icons"> <a href="#"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/facebook-footer-icon.jpg" ></a> <a href="#"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/twitter-facebook-icon.jpg" ></a> <a href="#"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/linkdin-footer-icon.jpg" ></a> <a href="#"><img src="https://1673045648.rsc.cdn77.org/seeitfirst/booking/images/github-footer-icon.jpg" ></a> </div>
  </div>
</footer>
<input type="date" name="odb">

</body>
</html>
<?php

function update_codes($uid,$code1)
{
	global $db,$table,$errors,$promoid,$promo_codes,$unique_code;
	global $assign_code_table,$Code,$add_message,$ucode_req ;
	// Update code table 
	$update_data = array();
	$update_data['user_id']=$uid;
	$update_data['use_date']='now()';
	$update_data['Code']="X$Code";
	
	if($ucode_req==2 )
	{
		$ucode_split = explode('-',$unique_code);
		$db->open_where();
		$db->where(array('location_id'=> 0));
		$db->or_where(array('location_id'=>$ucode_split[2]));
		$db->close_where();	
	}
	$db->where(array('user_id'=>0,'Code'=>$Code));
	$db->limit(1);
	$db->update($assign_code_table,$update_data);

//	mysql_query("update $assign_code_table set user_id=$uid,use_date=now(),Code='X$Code' where user_id=0 and Code='$Code' limit 1") or die('770'.mysql_error());

	$db->from($assign_code_table);
	$db->where(array('user_id'=>$uid,'Code'=>"X$Code"));
	$row=$db->fetch_first();

	//$q="select * from $assign_code_table where user_id='$uid' AND Code='X$Code'";
	//$qr=mysql_query($q) or die("<b>Error occured<br>Query:</b>$q2<br>".mysql_error());

	if($db->_result!=false && !empty($row['uniquevalue']))
	{
		if($row['uniquevalue']!=$code1)
		{
			$update_data = array();
			$update_data['custom3']=$row['uniquevalue'];
			$add_message = $row['add_message'];
			$db->where(array('id'=>$uid));
			$db->limit(1);
			$db->update($table,$update_data);			
			return true;
		}
		return true;
	}
	else
	{
		$update_data = array();
		$update_data['custom3']='NO_STOCK';
		$db->where(array('id'=>$uid));
		$db->limit(1);
		$db->update($table,$update_data);

		//mysql_query("update $table set custom3='NO_STOCK' where id=$uid limit 1")or die(' 793 '.mysql_error());
		//$data = array('promo_codes'=>'NO_STOCK');
		//$db->where('id',$uid);
		//$db->limit(1);
		//$db->update( $table['competition_data'],$data,1);
	
		$errors['full']='Sorry, all available items have now been claimed.';
		return false;
	}
}
?>
<script>
	function toggletc()
	{
		$(".termcondition p").fadeToggle(1000);
		$("#terms_link").toggleClass("textarrow-up terms-textarrow");
	}
	$('.toggleDiv').click(function(e) {

	$(this).toggleClass("textarrow-up terms-textarrow");

	target_div=$(this).attr('target_div');

	$('#'+target_div).toggle(600);

	});
</script>

<?  if(count($errors) > 0 && $caller=='Submit') { ?>
<script language="javascript">
$( "#err_div" ).fadeOut(20000);
</script>
<? } ?>
 