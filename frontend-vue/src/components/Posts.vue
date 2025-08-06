<template>
    <!-- <div class="container mt-4">
      <h2>Posts</h2>
      <div v-if="loading" class="text-center">
        <div class="spinner-border" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
  
      <div v-else>
        <div class="row">
          <div class="col-md-4" v-for="post in posts" :key="post.id">
            <div class="card mb-4">
              <div class="card-body">
                <h5 class="card-title">{{ post.title }}</h5>
                <p class="card-text">{{ post.content }}</p>
                <p class="text-muted">Created at: {{ post.created_at }}</p>
              </div>
            </div>
          </div>
        </div>
  
        <div v-if="pagination.total > 0" class="d-flex justify-content-center">
          <nav>
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: !pagination.links[0].active }">
                <a class="page-link" href="#" @click.prevent="changePage(pagination.links[0].url)">&laquo; Previous</a>
              </li>
              <li v-for="link in pagination.links" :key="link.label" :class="{ active: link.active }" class="page-item">
                <a class="page-link" href="#" @click.prevent="changePage(link.url)">{{ link.label }}</a>
              </li>
              <li class="page-item" :class="{ disabled: !pagination.links[2].active }">
                <a class="page-link" href="#" @click.prevent="changePage(pagination.links[2].url)">Next &raquo;</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div> -->
  </template>
  
  <!-- <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        posts: [],
        pagination: {
          current_page: 1,
          total: 0,
          links: [],
        },
        loading: true,
      };
    },
    created() {
      this.fetchPosts();
    },
    methods: {
      fetchPosts(page = 1) {
        this.loading = true;
        axios
          .get(`http://127.0.0.1:8000/api/posts?page=${page}`)
          .then(response => {
            this.posts = response.data.data;
            this.pagination = response.data;
          })
          .catch(error => {
            console.error("There was an error fetching the posts:", error);
          })
          .finally(() => {
            this.loading = false;
          });
      },
      changePage(url) {
        if (url) {
          const page = new URL(url).searchParams.get('page');
          this.fetchPosts(page);
        }
      }
    }
  };
  </script> -->
  
  <!-- <style scoped>
  .card-title {
    font-size: 1.25rem;
    font-weight: bold;
  }
  .card-text {
    font-size: 1rem;
    color: #555;
  }
  </style> -->
  