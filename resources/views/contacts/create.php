<h1>Create Contact Form</h1>
<form method="post" action="/contacts">

  <div class="mb-3">
    <label class="form-label">Name</label>
    <input value="<?= old('name') ?>" name="name" type="text" class="form-control">
    <div class="text-danger"><?= error('name') ?></div>
  </div>

  <div class="mb-3">
    <label class="form-label">Phone Number</label>
    <input value="<?= old('phone_number') ?>" name="phone_number" type="text" class="form-control">
    <div class="text-danger"><?= error('phone_number') ?></div>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
