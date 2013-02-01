<form action="admin/review" method="POST">
    <input type="hidden" name="review" value="{{ $review }}">
    <input type="hidden" name="profile" value="{{ $profile_type }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="submit" value="Approve" class="btn btn-success">
</form>