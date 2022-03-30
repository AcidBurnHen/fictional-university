import $ from 'jquery';

class Like {
    constructor() {
        this.events(); 
    }

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    // Methods

    ourClickDispatcher(e) {
        var currentLikeBox = $(e.target).closest(".like-box"); 

        if (currentLikeBox.attr('data-exists') == 'yes') { /* .data attribute is only sent at page load, for interactivity use .attr  */
            this.deleteLike(currentLikeBox); /* Passes along the currentlikebox var to the other method */
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) { /* Passed on var needs to be mentioned */
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST',
            data: {'professorId': currentLikeBox.data('professor')}, /* Sends data to hook up with server side, adds ?professorId=?? to api ULR uses currentLikeBox to fetch id*/
            success: (response) => {
                currentLikeBox.attr('data-exists', 'yes');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10) ; /*parseInt is a JS tool that converts string to text */
                likeCount++; /* Increment */
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", response);
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            data: {'like': currentLikeBox.attr('data-like')},
            type: 'DELETE',
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10) ; /*parseInt is a JS tool that converts string to text */
                likeCount--; /* Decrease by 1 */
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", '');
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Like;