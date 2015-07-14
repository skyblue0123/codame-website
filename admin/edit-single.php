<? 

include('head.php');
include('form-submit.php');
 
$action = $_GET['action'];
$table   = $_GET['table'];
$slug    = $_GET['slug'];
$noun = ucfirst(substr($table, 0, -1)); // noun for what is being edited. artist, etc

if( $action == "edit" ){
  
  $content = get_row($table,'slug',$slug);
  $pic = get_image_size('small',$content['pic']);

  $view_link = " / <a href='$site_url/$table/$slug'>View Page</a>";

}

?>
<body>

    <? include('sidebar.php'); ?>

    <div id="content">
      
      <?

      // print_r($content);

      ?>

      <h1>
        <?
        echo $_GET['action'] . " " . $noun;
        echo $view_link;
        ?>
      </h1>
      
      <form method='post' enctype="multipart/form-data">
        

        <!-- Hidden form fields -->

        <!-- Tells form-submit.php what table to update -->
        <input type="hidden" name="table" value="<? echo $table ?>" />
        
        <!-- edit or add -->
        <input type="hidden" name="action" value="<? echo $action ?>" />
        
        <!-- this slug is used when editing an existing entry. New slug gets generated from the new name of the entry. -->
        <input type="hidden" name="slug" value="<? echo $slug ?>" />
        
        <!-- pic value. saved here because it would get overwritten by an empty value -->
        <input type="hidden" name="pic" value="<? echo $content['pic'] ?>" />
        
        <!-- curl main pic. use this to download a pic from the net as the main image -->
        <input type="hidden" name="curl-main-pic" value="" />
        
        <!-- API Key. Verifies that the form submission is from our server. -->
        <input type="hidden" name="api-key" value="<? echo $codame_api_key ?>" />

        <!-- Shared fields. Name, pic -->

        <label>
          <span><? echo $noun ?> Name <b>*</b></span>
          <input type="text" name="name" placeholder="Name" value="<? echo $content['name'] ?>" />
        </label>

        <? 
          if($content['pic']){
            echo "<img class='thumbnail' width='150' src='$pic' />";
          }
        ?>

        <label>
          <span>Main Picture <b>*</b></span>
          <input type="file" name="pic" value="<? echo $content['pic'] ?>" />
        </label>

        <!-- Fields for artists only -->

        <? if( $table == 'artists' ){ ?>
        
        <label>
          <span>Artist Email Address</span>
          <input type="text" name="email" placeholder="Email Address" value="<? echo $content['email'] ?>"/>
        </label>

        <? } ?>

        <!-- Fields for artists and projects only -->

        <? if( $table == 'artists' || $table == 'projects'){ ?>

        <label>
          <span><? echo $noun ?> Website URL</span>
          <input type="text" name="website" placeholder="Website" value="<? echo $content['website'] ?>"/>
        </label>

        <label>
          <span><? echo $noun ?> Twitter URL</span>
          <input type="text" name="twitter" placeholder="http://twitter.com/<? echo $noun ?>" value="<? echo $content['twitter'] ?>"/>
        </label>

        <? } ?>

        <!-- Fields for events and projects only. (Artists list) -->

        <? if( $table == 'events' || $table == 'projects' ){ ?>

        <label>
          <span>Artists Involved</span>
          <input name="artists-array" id="artists-array" value="<? echo $content['artists_array']; ?>" />
        </label>

        <? } ?>

        <!-- Fields for events only -->

        <? if( $table == 'events' ){ ?>

        <label>
          <span>Projects Represented</span>
          <input name="projects-array" id="projects-array" value="<? echo $content['projects_array']; ?>" />
        </label>

        <label>
          <span>Event Date <b>*</b></span>
          <input type="date" name="date" value="<? echo $content['date'] ?>" />
        </label>

        <? } ?>


        <label>
          <span> Description <b>*</b></span>
          <textarea name="description" />
            <? echo $content['description'] ?>
          </textarea>
        </label>

        <input type="submit" />

      </form>

    </div>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        height: 500,
        // theme:'advanced',
        relative_urls : false,
        plugins : 'advlist autolink link image media jbimages code textcolor pagebreak table',
        toolbar: "jbimages | styleselect | table | forecolor backcolor | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | pagebreak | bullist numlist outdent indent | link unlink | removeformat | code",
     });
  </script>

  <!-- JS for events and projects. Allows tagging artists onto events and projects -->
  
  <? if( $table == 'events' || $table == 'projects' ){ ?>
  
  <!-- jquery UI for autocomplete -->
  <script src="js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="js/jquery-ui-1.11.4/jquery-ui.min.css" />
  
  <!-- tag cloud widget -->
  <script src="js/jquery.tag-editor.min.js"></script>
  <script src="js/jquery.caret.min.js"></script>
  <script>
    <?
    // print the list of artists in an array for autocompletion
    $artists = get_table('artists',0,0);
    echo "artists = [";
    while($artist = mysqli_fetch_assoc($artists)){
      echo "'".$artist['slug']."', ";
    }
    echo "];";
    
    // if this is an event page, also print a list of all available projects
    if( $table == 'events' ){
     
      $projects = get_table('projects',0,0);
      echo "projects = [";
      while($project = mysqli_fetch_assoc($projects)){
        echo "'".$project['slug']."', ";
      }
      echo "];\n"; ?>

      // activate the projects picker
      $('#projects-array').tagEditor({
        autocomplete: {
          delay: 0,
          source: projects
        }
      });

    <? } ?>

    // activate the artists picker
    $('#artists-array').tagEditor({
      autocomplete: {
        delay: 0,
        source: artists
      }
    });

  </script>
  <? } ?>
</body>