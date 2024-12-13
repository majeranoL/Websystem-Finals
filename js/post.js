        // Open the Add Post Modal
        function openAddPostModal() {
            const modal = document.getElementById('addPostModal');
            modal.style.display = 'flex';
        }

        // Open the Edit Post Modal
        function openEditPostModal(postId) {
            fetch('app/posts/fetch_post_data.php?post_id=' + postId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate modal fields with fetched data
                        document.getElementById('postTitle').value = data.post.title;
                        document.getElementById('postDescription').value = data.post.content;
                        document.getElementById('postCategory').value = data.post.category;
                        document.getElementById('currentImage').innerHTML = 'Current image: <img src="' + data.post.image_url + '" alt="Post Image" width="100">';

                        // Set the form's action to point to the correct edit_post.php URL
                        document.getElementById('editPostForm').action = 'app/posts/edit_post.php?post_id=' + postId;

                        // Display the Edit Post Modal
                        document.getElementById('editPostModal').style.display = 'flex';
                    } else {
                        console.error('Error fetching post data: ', data.message);
                    }
                })
                .catch(error => console.error('Error fetching post data:', error));
        }

        // Close modal function
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Close modal when clicking outside the modal content
        window.onclick = function(event) {
            const editModal = document.getElementById('editPostModal');
            const addModal = document.getElementById('addPostModal');
            if (event.target === editModal) {
                closeModal('editPostModal');
            }
            if (event.target === addModal) {
                closeModal('addPostModal');
            }
        };

        // Close the Add Post Modal
        function closeAddPostModal() {
            const modal = document.getElementById('addPostModal');
            modal.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle dropdown visibility when clicking the button
            document.querySelectorAll('.post-dropdown-toggle').forEach(function(dropdownToggle) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event from bubbling
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
                });
            });

            // Close dropdown if clicked outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.post-dropdown-menu').forEach(function(menu) {
                    menu.style.display = 'none';
                });
            });
        });
