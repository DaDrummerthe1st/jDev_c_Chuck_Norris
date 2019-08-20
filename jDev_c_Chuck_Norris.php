<?php
/*
    * Plugin Name: jDev see about Chuck Norris Widget
    * Plugin URI: https://example.com/jDev_c_Chuck_Norris/
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
            
            ?>
            </select>
        </p>
        <?php
    }

}

function jDevcChuckNorrisretrieveCategories () {
    $jDevcChuckNorrisUrl = 'https://api.chucknorris.io/jokes/categories';
    
    $jDevcChuckNorrisResult = file_get_contents($jDevcChuckNorrisUrl);
    // Will dump a beauty json :3
    $jDevcChuckNorrisChuckCategories = json_decode(file_get_contents($jDevcChuckNorrisUrl));
    // $jDevcChuckNorrisChuckCategories = gettype($jDevOneThing) . $jDevOneThing[1];

    
    return $jDevcChuckNorrisChuckCategories;
}

?>