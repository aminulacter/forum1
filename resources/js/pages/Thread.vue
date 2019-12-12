
<script>
import Replies from "../components/Replies"
import SubscribeButton from "../components/SubscribeButton"
    export default {
        props: ['thread'],
        components: {Replies, SubscribeButton},
        data()
        {
            return{
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
                form: {}
            }
        },
        created() {
            this.resetForm()
        },
        methods:{
            togglelock()
            {
                let uri = `/locked-threads/${this.thread.slug}`
                axios[this.locked ? 'delete' : 'post'](uri);
                this.locked = !this.locked;


            },
            update()
            {
                let uri =`/threads/'${this.thread.channel.slug}/${this.thread.slug}`
                axios.patch(uri,this.form).then(response => {
                    flash("your Thread has been updated")
                    this.editing = false
                    })
            },
            resetForm()
            {
                 
               this.form= {
                    title: this.thread.title,
                    body: this.thread.body
                }
                this.editing = false
            }

        }
        
    }
</script>

<style lang="scss" scoped>

</style>