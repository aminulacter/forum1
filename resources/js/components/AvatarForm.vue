<template>
    <div>
        <div class="level">
            <img :src="avatar" alt="" height="50" width="50" class="mr-1">
            <h1>{{ user.name }}</h1>
        </div>
        
               
                   <!-- <form v-if="canUpdate"                  
                   enctype="multipart/form-data"> -->
                   <image-upload v-if="canUpdate" name="avatar" @loaded = onLoad></image-upload>
                   <!-- <input type="file" name="avatar" id="" accept="image/*" @change="onChange"> -->
                  
                <!-- </form> -->
               
                
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue'
    export default {
        props: ['user'],
        components: {ImageUpload},
        data:function(){
            return{
                 avatar: this.user.avatar_path
            }
        },
        computed:{
            
            canUpdate()
            {
              return  this.authorize(user=> user.id ===this.user.id)
                
            },

            
        },
        methods :{
            onLoad(data)
            {
                // if(!e.target.files.length) return;

                // let avatar = e.target.files[0];
                // let reader = new FileReader();
                // reader.readAsDataURL (avatar);
                // reader.onload = e => this.avatar = e.target.result
                //PERSISTS TO THE SERVER
                //console.log(e)
                this.avatar = data.src

                 this.persists(data.file);    
                
            },
            persists(avatar){
                let data = new FormData();
                data.append('avatar', avatar);
                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(({data}) => {flash(data.message)
                        console.log(data)
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>