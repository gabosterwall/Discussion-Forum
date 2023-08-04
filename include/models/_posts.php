<div class="posts">

    <button class="ajax-btn" id="exit-posts">Go Back</button>

    <h2>Posts</h2>

    <div class="post-container"></div>

    <form id="postForm" method="POST">

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" spellcheck="true" required placeholder="Enter a title...">

        <label for="description">Description:</label>
        <textarea type="text" name="description" id="description" cols="30" rows="10" spellcheck="true" required placeholder="Type something..."></textarea>
        
        <input id="submit-post-button" type="submit" value="Publish">

        <div class="message-box"></div>

    </form>

    <button class="ajax-btn" id="toggle-posts">+ Add Post</button>

</div>