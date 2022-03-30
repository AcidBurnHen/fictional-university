<?php 

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
    register_rest_route('university/v1', 'manageLike' , array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike' , array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) { // incoming $data from the rest route
    if (is_user_logged_in()) {
        $professor = sanitize_text_field($data['professorId']); // access the value from like.js, sanitize for security
    
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                  'key' => 'liked_professor_id',
                  'compare' => '=',
                  'value' => $professor
                )
            )
          ));
        
        if ($existQuery->found_posts == 0 AND get_post_type($professor) == "professor") {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '2nd Php Create Post Test',
                'meta_input' => array(
                    'liked_professor_id' => $professor // key of advanced custom fiield + value
                )
                ));
        } else {
            die ("inavlid prof id");
        }
    
    } else {
        die("Only logged in users can live");
    }
}

function deleteLike($data) {
  $likeId = sanitize_text_field($data['like']);
  if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') { // conditional security to prevent hack
    wp_delete_post($likeId, true); 
    return 'Congrats, like deleted';
  } else {
      die("You do not have permission to delete that.");
  }

}