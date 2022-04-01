<template>
  <div>
    <h3 class="text-center">All Users</h3>
    <br />
    <div>
      <input type="file" @change="uploadFile" ref="file" />
      <button @click="submitFile">Upload!</button>
      <button @click="exportToCsv">Export to Csv</button>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id">
          <td>{{ post.id }}</td>
          <td>{{ post.title }}</td>
          <td>{{ post.first_name }}</td>
          <td>{{ post.last_name }}</td>
          <td>{{ post.email }}</td>
          <td>
            <div class="btn-group" role="group">
              <router-link
                :to="{ name: 'edit', params: { id: post.id } }"
                class="btn btn-primary"
                >Edit
              </router-link>
              <button class="btn btn-danger" @click="deletePost(post.id)">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <p>
<button @click="prevPage">Previous</button>
<button @click="nextPage">Next</button>
</p>
  </div>
</template>



<script>
export default {
  data() {
    return {
      posts: [],
      images: null,
      currentSort: "name",
      currentSortDir: "asc",
      pageSize: 3,
      currentPage: 1,
      nextPageUrl: null,
      previousPageUrl: null
    };
  },

  created() {
    this.axios.get("http://localhost:8001/api/users").then((response) => {
      // console.log(response.data.data);
      this.posts = response.data.data;
      this.nextPageUrl = response.data.next_page_url;
      this.previousPageUrl = response.data.prev_page_url;
    });
  },
  methods: {
    nextPage: function () {
       this.axios.get(this.nextPageUrl).then((response) => {
      // console.log(response.data.data);
      this.posts = response.data.data;
      this.nextPageUrl = response.data.next_page_url;
      this.previousPageUrl = response.data.prev_page_url;
    });
    },
    prevPage: function () {
      this.axios.get(this.previousPageUrl).then((response) => {
      // console.log(response.data.data);
      this.posts = response.data.data;
      this.nextPageUrl = response.data.next_page_url;
      this.previousPageUrl = response.data.prev_page_url;
    });;
    },
    deletePost(id) {
      this.axios
        .delete(`http://localhost:8001/api/users/delete/${id}`)
        .then((response) => {
          let i = this.posts.map((item) => item.id).indexOf(id); // find index of your object
          this.posts.splice(i, 1);
        });
    },
    uploadFile() {
      this.Images = this.$refs.file.files[0];
    },
    submitFile() {
      const formData = new FormData();
      formData.append("file", this.Images);
      const headers = { "Content-Type": "multipart/form-data" };
      axios
        .post("http://localhost:8001/api/uploadUsers", formData, { headers })
        .then((res) => {
          res.data.files; // binary representation of the file
          res.status; // HTTP status
        });
    },
    exportToCsv() {
      axios({
        url: "http://localhost:8001/api/exportUsers", //your url
        method: "GET",
        responseType: "blob", // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", "file.csv"); //or any other extension
        document.body.appendChild(link);
        link.click();
      });
    },
  },
};
</script>
