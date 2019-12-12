
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
                locked: this.thread.locked
            }
        },
        methods:{
            togglelock()
            {
                console.log(this.locked)
                axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.thread.slug);
                this.locked = !this.locked;


            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>