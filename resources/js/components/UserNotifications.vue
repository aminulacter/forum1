<template>
   <li class="nav-item dropdown" v-if="notifications">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                 <a class="dropdown-item" v-for="notification in notifications"
                  :key="notification.id" 
                  :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"></a>     
                                   
                                
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            }
        },
        created() {
            this.getNotifications()
        },
        methods: {
            markAsRead(notification)
            {
                axios.delete("/profiles/" + window.App.user.name + "/notifications/" + notification.id)
                this.getNotifications()
            },
            getNotifications()
            {
                axios.get("/profiles/"+ window.App.user.name + "/notifications")
                 .then(({data}) =>  this.notifications = data.length? data : false)
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>