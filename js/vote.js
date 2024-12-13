  document.addEventListener('DOMContentLoaded', function() {
            const upvoteButtons = document.querySelectorAll('.upvote');
            const downvoteButtons = document.querySelectorAll('.downvote');

            // Function to handle voting
      function handleVote(voteValue, postId) {
                const voteForm = document.querySelector(`form[data-post-id='${postId}']`);
                const voteCountElement = document.querySelector(`#post-${postId} .vote-count`);


                // Create a FormData object to send the form data (including vote value)
                const formData = new FormData(voteForm);
          formData.append('vote_value', voteValue);
          formData.append('post_id', postId);



                // Send the vote via AJAX
                fetch('/votes/HandleVotes.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        

                        if (data.success) {
                            // Update the vote count in the UI
                            voteCountElement.textContent = data.new_vote_count;

                            // Remove highlight classes
                            upvoteButton.classList.remove('voted');
                            downvoteButton.classList.remove('voted');

                            // Highlight the clicked button
                            if (voteValue === 1) {
                                upvoteButton.classList.add('voted');
                            } else if (voteValue === -1) {
                                downvoteButton.classList.add('voted');
                            }
                        } else {
                            alert('Error: Unable to vote.');
                        }
                    })
                    .catch(error => {
                        console.error('Error voting:', error);
                        // Re-enable buttons in case of an error
                        upvoteButton.disabled = false;
                        downvoteButton.disabled = false;
                    });
            }

            // Attach event listeners to upvote and downvote buttons
            upvoteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent form submission


                    const postId = this.closest('.vote-form').getAttribute('data-post-id');
                    handleVote(1, postId);
                });
            });

            downvoteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent form submission
                    const postId = this.closest('.vote-form').getAttribute('data-post-id');
                    handleVote(-1, postId);
                });
            });
        });