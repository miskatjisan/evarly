<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link to="/" class="navbar-brand">E-Exam</router-link>
            <a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-lg-0 me-auto mb-2 mb-lg-0">
                    <LocaleSwitcher />
                </ul>
                <ul class="navbar-nav mt-2 mt-lg-0 ms-auto">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link" aria-current="page">{{ $t('home') }}</router-link>
                        </li>
                        <!-- <li class="nav-item">
                            <router-link :to="{ name : 'public-posts.index'}" class="nav-link">Posts</router-link>
                        </li> -->
                    <template v-if="!user?.name">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/login"
                            >{{ $t('login') }}</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/register">{{ $t('register') }}</router-link>
                        </li>
                    </template>
                    <!-- <li v-if="user?.name" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ user.name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><router-link class="dropdown-item" to="/admin">Admin</router-link></li>
                            <li><router-link to="/admin/posts" class="dropdown-item">Post</router-link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a></li>
                        </ul>
                    </li> -->


                    <div class="dropdown-wrapper position-relative" ref="dropdownRef">
    <button class="btn btn-light dropdown-toggle" @click="toggleDropdown">
      Hi, {{ user.name }}
    </button>
    <ul v-if="isOpen" class="dropdown-menu show position-absolute end-0 mt-2 shadow-sm">
      <li>
        <router-link :to="{ name: 'profile.index' }" class="dropdown-item">Profile</router-link>
      </li>
      <li><a class="dropdown-item" href="#">Setting</a></li>
      <li><hr class="dropdown-divider" /></li>
      <li>
        <a class="dropdown-item" :class="{ 'opacity-50': processing }" href="javascript:void(0)" @click="logout" :disabled="processing">Logout</a>
      </li>
    </ul>
  </div>


                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { useStore } from 'vuex';
import useAuth from '@/composables/auth';

const store = useStore();
const user = computed(() => store.state.auth.user);
const { logout, processing } = useAuth();

const isOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
