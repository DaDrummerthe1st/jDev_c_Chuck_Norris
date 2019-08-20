<?php
/*
    * Plugin Name: jDev see about Chuck Norris Widget
    * Plugin URI: https://api.github.com/repos/DaDrummerthe1st/jDev_c_Chuck_Norris
    * Description: Berättelser om Chuck Norris
    * Version: 1.0
    * Author: Joakim Reuterborg
    * Author URI: https://reuterborg.se/
    * License: GPLv2
*/

/*
source: https://codex.wordpress.org/Widgets_API
In technical terms: a WordPress Widget is a PHP object that echoes string data to STDOUT when its widget() method is called. It's located in wp-includes/widgets.php.
*/

// Widgets only exists in classes
add_action('widgets_init', function(){ // In order for this to show up on the sidebar
        register_widget('jDev_c_Chuck_Norris'); // Creates the class we want to register
    });

// dashboard menu handler
add_action( 'admin_menu', 'jDevcChuckNorrisCreateMenu');

class jDev_c_Chuck_Norris extends WP_Widget { // extends inherits another standard class thus inheriting its possibilities ie drag-n-drop
    
    // Constructor is the function always executed when a new instance of the class is created
    // Constructor is a component of object oriented programming
    // public = anyone and anything can use and create an instance of this class
    public function __construct(){ // underscore*2construct is the standard name
        
        parent::WP_Widget( // parent:: means This class is the child of WP_Widget
        // These arguments are mandatory
            false, // ???
            'jDev see about Chuck Norris Widget', // Base ID, has to be unique, preferably for the whole Wordpress universe since it will be                                  displayed within the plugin list and Wordpress will try to look for updates within the                                   plugin update forum
            'description=Berättelser om Chuck Norris' // Description will be displayed in the plugin list
        ); 
    }

    // This function has to be named widget. It prints everything to the (public) website
    // idea: https://api.chucknorris.io/jokes/random
    function widget ($args, $instance){
        // Retrieves all arguments. Best practice is to write more than what will be displayed (see before_widget below)

        extract ($args);
        echo $before_widget; // normally a <li> tag
        echo $before_title . $instance['title'] . $after_title; // normally a h3-tag
       
        echo "<p class='jDev_c_Chuck_Norris'>"; // preferrably use a class for later css purpose!
            // PUBLIC WIDGET CONTENT:
        ?>
            <p>
                Go see jDev!
            </p>
        <?php
        echo "</p>";
        echo $after_widget; // normally a </li> tag
    }

    function update ($new_instance, $old_instance) { // Handles the updates within the widget, at the change of a title for example
        return $new_instance;
    }

    function form() { // this is where things are set in the dashboard / widgets
        $title = esc_attr($instance['title']); // esc_att() erases potential malicious input
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); // this means the active instance of this class ?>">
               
                Titel:
                <input 
                    type="text"
                    class="widefat" <?php // makes the area completly fill out its parent ?>
                    name="<?php echo $this->get_field_name('title');?>" 
                    id="<?php echo $this->get_field_id('title');?>"
                />

            </label>
        </p>
        <?php
    }

}

function jDevcChuckNorrisCreateMenu() {
    
    /********************************
     * create custom top level menu *
     * ******************************/
    
     add_menu_page( 'Chuck Norris Settings', 'jDev - Chuck Norris', 'manage_options', 'jDev_c_Chuck_Norris', 'jDev_c_Chuck_Norris_settings_page');
    // add_menu_page( $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable, $icon_url:string, $position:integer|null )

    // create submenu items
    // add_submenu_page( $parent_slug:string, $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable )
    add_submenu_page( 'jDev_c_Chuck_Norris', 'General settings page', 'General Settings menu', 'manage_options', 'jDev_c_Chuck_Norris_settings', 'jDev_c_Chuck_Norris_Settings' );
    add_submenu_page( 'jDev_c_Chuck_Norris', 'About page', 'About menu', 'manage_options', 'jDev_c_Chuck_Norris_about', 'jDev_c_Chuck_Norris_about_page' );
    add_submenu_page( 'jDev_c_Chuck_Norris', 'Uninstall page', 'Uninstall menu', 'manage_options', 'jDev_c_Chuck_Norris_uninstall', 'jDev_c_Chuck_Norris_uninstall_page' );
    
    /********************************
     * create custom submenu *
     * ******************************/
    
    add_options_page( 'jDev Settings', 'jDev Settings Page', 'manage_options', 'jDev_more_settings', 'jDev_c_Chuck_Norris_Settings' );
}

function jDev_c_Chuck_Norris_Settings() {
?>
    <h1>Inställningar för Chuck Norris berättelserna</h1>
    <p class="jDev_c_Chuck_Norris_Settings">
    <h5>Välj kategori</h5>
    <select>
        <?php
        $retrievedCat = jDevcChuckNorrisretrieveCategories();
        $countRetrievedCat = count($retrievedCat);
        // Read the list of categories available for display
            for($i = 0; $i < $countRetrievedCat; $i++) {
                echo "<option value='$i'>";
                echo $retrievedCat[$i];
                echo "</option>";
            }
        
    ?></select>
    </p><?php
}

function jDevcChuckNorrisretrieveCategories () {
    $jDevcChuckNorrisUrl = 'https://api.chucknorris.io/jokes/categories';
    
    // $jDevcChuckNorrisResult = file_get_contents($jDevcChuckNorrisUrl);
    // Will dump a beauty json :3
    $jDevcChuckNorrisCategories = json_decode(file_get_contents($jDevcChuckNorrisUrl));
    // $jDevcChuckNorrisCategories = gettype($jDevOneThing) . $jDevOneThing[1];
    
    return $jDevcChuckNorrisCategories;
}

?>