<?php
/*
 NOTE:
 There are THREE components that make up the functionality of users.php.
 1. users.php
  - contains the tabs
  - lists users
  - has an iframe for adding/editing users
  - include statements for groups.php and nonusers.php
 2. edit_user.php
  - the contents of the iframe (i.e. a form for adding/editing users)
 3. edit_user_handler.php
  - handles form submittal from edit_user.php
  - provides user with confirmation of successful operation
  - refreshes the parent frame (users.php)

 This structure is mirrored for groups & nonusers
*/

/* $Id$ */

include_once 'includes/init.php';

if ( empty ( $login) || $login == '__public__' ) {
  // do not allow public access
  do_redirect ( empty ( $STARTVIEW ) ? 'month.php' : $STARTVIEW );
  exit;
}

$INC = array('js/users.php/true','js/visible.php/true');
$BodyX = ( ! empty ( $tab ) ?  "onload=\"showTab( '$tab' );\"" : '"' );
print_header($INC,'', $BodyX );


?>
<a title="<?php etranslate( 'Admin' ) ?>" class="nav" href="adminhome.php">&laquo;&nbsp;<?php etranslate( 'Admin' ) ?></a><br /><br />

<!-- TABS -->
<div id="tabs">
 <span class="tabfor" id="tab_users"><a href="#tabusers" onclick="return showTab('users')"><?php 
  if ($is_admin) {
   echo translate( 'Users' );
  } else {
   echo translate( 'Account' );
  }
 ?></a></span>
 <?php if ($GROUPS_ENABLED == 'Y' && $is_admin) { ?>
  <span class="tabbak" id="tab_groups"><a href="#tabgroups" onclick="return showTab('groups')"><?php etranslate( 'Groups' )?></a></span>
 <?php } 
 if ($NONUSER_ENABLED == 'Y' && $is_admin) { ?>
  <span class="tabbak" id="tab_nonusers"><a href="#tabnonusers" onclick="return showTab('nonusers')"><?php etranslate( 'NonUser Calendars' )?></a></span>
 <?php } 
 if ($REMOTES_ENABLED == 'Y'  && ( ! access_is_enabled () || 
   access_can_access_function ( ACCESS_IMPORT ) )) { ?>
  <span class="tabbak" id="tab_remotes"><a href="#tabremotes" onclick="return showTab('remotes')"><?php etranslate( 'Remote Calendars' )?></a></span>
 <?php } ?>
</div>

<!-- TABS BODY -->
<div id="tabscontent">
 <!-- USERS -->
 <a name="tabusers"></a>
 <div id="tabscontent_users">
 <?php if ( $is_admin ) {
  
   if ( $admin_can_add_user )
    echo '<a title="' . 
     translate( 'Add New User' ) . "\" href=\"edit_user.php\" target=\"useriframe\" onclick=\"javascript:show('useriframe');\">" . 
     translate( 'Add New User' ) . "</a><br />\n";
  ?>
  <ul>
   <?php
    $userlist = user_get_users ();
    for ( $i = 0, $cnt = count ( $userlist ); $i < $cnt; $i++ ) {
     if ( $userlist[$i]['cal_login'] != '__public__' ) {
      echo '<li><a title="' . 
       $userlist[$i]['cal_fullname'] . '" href="edit_user.php?user=' . 
       $userlist[$i]['cal_login'] . 
       "\" target=\"useriframe\" onclick=\"javascript:show('useriframe');\">";
      echo $userlist[$i]['cal_fullname'];
      echo '</a>';
      if (  $userlist[$i]['cal_is_admin'] == 'Y' )
       echo '&nbsp;<abbr title="' . translate( 'denotes administrative user' ) . 
       '">*</abbr>';
      echo "</li>\n";
     }
    }
   ?>
  </ul>
 *&nbsp;<?php etranslate( 'denotes administrative user' )?><br />

  <?php 
   echo '<iframe name="useriframe" id="useriframe" style="width:90%;border-width:0px; height:280px;"></iframe>';

} else { ?>
<iframe src="edit_user.php" name="accountiframe" id="accountiframe" style="width:90%;border-width:0px; height:210px;"></iframe>
<?php } ?>
</div>

<?php 
 if ($GROUPS_ENABLED == 'Y' && $is_admin) { 
  include_once 'groups.php';
 } 
 if ($NONUSER_ENABLED == 'Y' && $is_admin) {
  include_once 'nonusers.php';
 }
  if ($REMOTES_ENABLED == 'Y' && ( ! access_is_enabled () ||
    access_can_access_function ( ACCESS_IMPORT ) ) ) {
  include_once 'remotes.php';
 }
?>
</div>

<?php echo print_trailer(); ?>
</body>
</html>
