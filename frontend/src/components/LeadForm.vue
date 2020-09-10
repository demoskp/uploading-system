<template>
  <div>
    <div class="container">
      <form @submit.prevent="submitQuote">

        <div class="columns">
          <div class="field column is-half">
            <label class="label">Name</label>
            <div class="control">
              <input v-model="form.name" class="input" type="text" placeholder="Name" name="name">
            </div>
          </div>
          <div class="field column is-half">
            <label class="label">Email</label>
            <div class="control">
              <input v-model="form.email" class="input" type="email" placeholder="Email" name="email">
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="field column is-half">
            <label class="label">Phone</label>
            <div class="control">
              <input v-model="form.phone" class="input" type="number" placeholder="Phone" name="phone">
            </div>
          </div>
          <div class="field column is-one-third">
            <label class="label">3D File</label>

            <div class="file">
              <label class="file-label">
                <input class="file-input" type="file" name="part" @change="getCadThumbnail">
                <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Choose a 3d file…
              </span>
            </span>
              </label>
            </div>
          </div>

          <div class="field column is-one-third">
            <div id="uploaded_3d_file"></div>
          </div>
        </div>

        <div class="columns">
          <div class="field column is-half">
            <label class="label">Phone</label>
            <div class="control">
              <textarea v-model="form.more_info" class="textarea" placeholder="More Info"></textarea>
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="field column is-half">
            <button class="button is-primary">Request Quote</button>
          </div>
        </div>

      </form>

      <div id="form-success" class="pt-6"></div>
      <div id="form-errors" class="pt-6"></div>

    </div>


    <div id="form-lead-loading" class="is-overlay is-hidden middle-of-screen">
      <div class="container">
        <progress class="progress is-large is-info" max="100">60%</progress>
      </div>
    </div>

  </div>
</template>

<script>
import * as THREE from 'three'
import {STLLoader} from 'three/examples/jsm/loaders/STLLoader.js'

export default {
  name: "LeadForm",
  components: {},
  data: function () {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        more_info: '',
        part: ''
      }
    }
  },
  methods: {
    submitQuote() {
      this.resetForm()

      // Submit Form
      let formData = new FormData();
      formData.append('name', this.form.name)
      formData.append('email', this.form.email)
      formData.append('phone', this.form.phone)
      formData.append('more_info', this.form.more_info)
      formData.append('part', this.form.part)
      this.$store.dispatch('submitQuote',
          formData
      )
          // Process response
          .then(() => {
            this.clearForm()
            document.getElementById('form-lead-loading').classList.add('is-hidden');
            this.addSuccessMessage()
          })
          // Process any errors
          .catch((errors) => {
            document.getElementById('form-lead-loading').classList.add('is-hidden');
            this.addErrors(errors)
          })
    },

    resetForm () {
      document.getElementById('form-errors').innerHTML = '';
      document.getElementById('form-success').innerHTML = '';
      document.getElementById('form-lead-loading').classList.remove('is-hidden');
    },
    addErrors(errors) {
      Object.entries(errors).forEach(
          ([name, value]) => {
            document.getElementById('form-errors').innerHTML += ` <div class="notification is-danger is-light">
              ${value}
            </div>`;
          }
      );
    },

    addSuccessMessage() {
      document.getElementById('form-success').innerHTML = `   <div class="notification is-success is-light">
              Your quote has been submitted successfully
            </div>`;
    },

    clearForm() {
      this.form.name = ''
      this.form.email = ''
      this.form.phone = ''
      this.form.more_info = ''
      this.form.part = ''
    },

    getCadThumbnail(event) {
      let self = this;

      let camera, cameraTarget, scene, renderer;

      let container = document.getElementById('uploaded_3d_file');
      document.getElementById('uploaded_3d_file').innerHTML = "";
      // $('#uploaded_3d_file').empty();

      camera = new THREE.PerspectiveCamera(35, window.innerWidth / window.innerHeight, 1, 15);
      camera.position.set(3, 0.15, 3);

      cameraTarget = new THREE.Vector3(0, -0.25, 0);

      scene = new THREE.Scene();
      scene.background = new THREE.Color(0x72645b);
      scene.fog = new THREE.Fog(0x72645b, 2, 15);


      // Ground

      let plane = new THREE.Mesh(
          new THREE.PlaneBufferGeometry(40, 40),
          new THREE.MeshPhongMaterial({color: 0x999999, specular: 0x101010})
      );
      plane.rotation.x = -Math.PI / 2;
      plane.position.y = -0.5;
      scene.add(plane);

      plane.receiveShadow = true;


      let loader = new STLLoader();


      // Lights

      scene.add(new THREE.HemisphereLight(0x443333, 0x111122));

      this.addShadowedLight(scene, 1, 1, 1, 0xffffff, 1.35);
      this.addShadowedLight(scene, 0.5, 1, -1, 0xffaa00, 1);
      // renderer

      renderer = new THREE.WebGLRenderer({antialias: true});
      renderer.setPixelRatio(window.devicePixelRatio);

      renderer.gammaInput = true;
      renderer.gammaOutput = true;

      renderer.shadowMap.enabled = true;

      container.appendChild(renderer.domElement);


      let fileObject = event.target.files[0];
      this.form.part = fileObject;
      let reader = new FileReader();
      reader.onload = function () {

        let geometry = loader.parse(this.result);

        let material = new THREE.MeshPhongMaterial({color: 0xff5533, specular: 0x111111, shininess: 200});
        let mesh = new THREE.Mesh(geometry, material);

        mesh.position.set(0, -0.25, 0.6);
        mesh.rotation.set(0, -Math.PI / 2, 0);
        mesh.scale.set(0.5, 0.5, 0.5);

        mesh.castShadow = true;
        mesh.receiveShadow = true;

        scene.add(mesh);

        self.render(camera, renderer, scene, cameraTarget);


      };
      reader.readAsArrayBuffer(fileObject);


    },
    addShadowedLight(scene, x, y, z, color, intensity) {

      let directionalLight = new THREE.DirectionalLight(color, intensity);
      directionalLight.position.set(x, y, z);
      scene.add(directionalLight);

      directionalLight.castShadow = true;

      let d = 1;
      directionalLight.shadow.camera.left = -d;
      directionalLight.shadow.camera.right = d;
      directionalLight.shadow.camera.top = d;
      directionalLight.shadow.camera.bottom = -d;

      directionalLight.shadow.camera.near = 1;
      directionalLight.shadow.camera.far = 4;

      directionalLight.shadow.mapSize.width = 1024;
      directionalLight.shadow.mapSize.height = 1024;

      directionalLight.shadow.bias = -0.002;

    },
    render(camera, renderer, scene, cameraTarget) {

      camera.lookAt(cameraTarget);

      renderer.render(scene, camera);

    }
  }
}
</script>

<style scoped>
.middle-of-screen {
  top: 50%;
}
</style>