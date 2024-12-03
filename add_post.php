<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post Modal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
    }

    /* Modal overlay */
    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    /* Modal content box */
    .modal-content {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      width: 400px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .form-group textarea {
      resize: vertical;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-submit {
      background-color: #007bff;
      color: #fff;
    }

    .btn-cancel {
      background-color: #dc3545;
      color: #fff;
    }

    .btn:hover {
      opacity: 0.9;
    }

    /* Button to trigger modal */
    .open-modal-btn {
      display: inline-block;
      margin: 20px;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .open-modal-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<!-- Button to open modal -->
<button class="open-modal-btn" onclick="openModal()">Create Post</button>

<!-- Modal -->
<div class="modal" id="postModal">
  <div class="modal-content">
    <div class="modal-header">Create a Post</div>
    <form>
      <div class="form-group">
        <label for="postTitle">Post Title</label>
        <input type="text" id="postTitle" placeholder="Enter your title">
      </div>
      <div class="form-group">
        <label for="postDescription">Description</label>
        <textarea id="postDescription" rows="4" placeholder="Write your description"></textarea>
      </div>
      <div class="form-group">
        <label for="postImage">Image</label>
        <input type="file" id="postImage" accept="image/*">
      </div>
      <div class="form-group">
        <label for="postCategory">Category</label>
        <select id="postCategory">
          <option value="general">General</option>
          <option value="news">News</option>
          <option value="entertainment">Entertainment</option>
          <option value="education">Education</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-submit">Post</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Open modal function
  function openModal() {
    document.getElementById("postModal").style.display = "flex";
  }

  // Close modal function
  function closeModal() {
    document.getElementById("postModal").style.display = "none";
  }

  // Close modal when clicking outside the modal content
  window.onclick = function (event) {
    const modal = document.getElementById("postModal");
    if (event.target === modal) {
      closeModal();
    }
  };
</script>

</body>
</html>
