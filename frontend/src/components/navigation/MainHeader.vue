<template>
  <div class="has-background-dark">
    <nav class="navbar is-dark container" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item">
          <router-link :to="{ name: 'Home'}"></router-link>
          <img src="../../assets/geomiq-logo.png" width="112" height="28">
        </a>

        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false"
           data-target="navbarBasicExample">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
          <a class="navbar-item">
            <router-link :to="{ name: 'Home'}">Home</router-link>
          </a>

          <a class="navbar-item">
            <router-link :to="{ name: 'Quotes'}"></router-link>
            Quotes
          </a>

        </div>

      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "Header"
}
</script>

<style scoped>

</style>