<?
  include('admin/functions.php');

  // this GET variable is set in the .htaccess file
  // URLs are rewritten like this:
  // codame.com/artists -> codame.com/category.php?table=artists

  $table = $_GET['table'];

  if( $table == 'artists' || $table == 'projects' ){
    $order_by = 'edited';
  }else if( $table == 'events' ){
    $order_by = 'date';
  }else{
    // an invalid table was given. Redirect to home.
    header('Location:http://codame.com');
  }

?>
<html>
<? include('head.php'); ?>
<body>

  <? include('header.php') ?>
  <? include('sidebar.php') ?>
  <div id="content">

      <h2 class="bar-link">
        <? echo $table; ?>
      </h2>

      <div class="tiles">
        <? output_results($table,0,0,'tiles',$order_by); ?>
      </div>

  </div>
  <? include('footer.php') ?>

</body>
</html>