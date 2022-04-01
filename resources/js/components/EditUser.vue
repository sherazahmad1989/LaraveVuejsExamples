<template>
  <div>
    <h3 class="text-center">Edit User</h3>
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="updateUser">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" v-model="post.title" />
          </div>
          <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" v-model="post.first_name" />
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" v-model="post.last_name" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" v-model="post.email" />
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" v-model="post.phone" />
          </div>
          <div class="form-group">
            <label>city</label>
            <input type="text" class="form-control" v-model="post.city" />
          </div>
          <div class="form-group">
            <label>state</label>
            <input type="text" class="form-control" v-model="post.state" />
          </div>
          <div class="form-group">
            <label>country</label>
            <input type="text" class="form-control" v-model="post.country" />
          </div>
          <div class="form-group">
            <label>Post Code</label>
            <input type="text" class="form-control" v-model="post.post_code" />
          </div>
          <div class="form-group">
            <label>Gender</label>
            <input type="text" class="form-control" v-model="post.gender" />
          </div>
          <br />
          <div class="form-group">
            <label for="description">Picture</label>
            <input type="file" @change="uploadFile" ref="file" />
          </div>
          <br />
          <button type="submit" class="btn btn-primary">Add Post</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      post: {},
    };
  },
  created() {
    this.axios
      .get(`http://localhost:8001/api/users/${this.$route.params.id}`)
      .then((response) => {
        this.post = response.data;
        // console.log(response.data);
      });
  },
  methods: {
    uploadFile() {
      this.Images = this.$refs.file.files[0];
    },
    updateUser() {
  /*    this.axios
        .post(
          `http://localhost:8000/api/User/update/${this.$route.params.id}`,
          this.post
        )
        .then((response) => {
          this.$router.push({ name: "home" });
        });
    */
      const formData = new FormData();
      formData.append("file", this.Images);
      formData.append("title", this.post.title);
      formData.append("first_name", this.post.first_name);
      formData.append("last_name", this.post.last_name);
      formData.append("email", this.post.email);
      formData.append("phone", this.post.phone);
      formData.append("city", this.post.city);
      formData.append("state", this.post.state);
      formData.append("country", this.post.country);
      formData.append("post_code", this.post.post_code);
      formData.append("gender", this.post.gender);
      formData.append("id", this.$route.params.id);
      const headers = { "Content-Type": "multipart/form-data" };
      axios
        .post("http://localhost:8001/api/users", formData, { headers })
        .then((res) => {
          res.data.files; // binary representation of the file
          res.status; // HTTP status
        }).then((response) =>
          this.$router.push({ name: "home" })
          // console.log(response.data)
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

  },
};
</script>
