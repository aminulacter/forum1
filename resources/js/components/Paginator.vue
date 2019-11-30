<template>
   
  <ul class="pagination justify-content-start mt-3" v-if="shouldPaginate">
    <li class="page-item ">
      <a class="page-link" href="#" tabindex="-1" aria-disabled="true" rel="previous" @click.prevent="page--" v-if="prevUrl"> &laquo; Previous </a>
    </li>
    
    <li class="page-item">
      <a class="page-link" href="#" rel="next" @click.prevent="page++" v-if="nextUrl">Next &raquo;</a>
    </li>
  </ul>

</template>

<script>
    export default {
        props: ['dataSet'],
        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },
        computed: {
            shouldPaginate()
            {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },
        watch: {
            dataSet()
            {
                this.page = this.dataSet.current_page
                this.prevUrl = this.dataSet.prev_page_url
                this.nextUrl = this.dataSet.next_page_url
                console.log(this.dataSet.next_page_url)

            },
            page()
            {
                this.broadcast().updateUrl();
            }
        },
        methods: {
            broadcast()
            {
               return this.$emit('changed', this.page);
              
            },
            updateUrl()
            {
                history.pushState(null, null, '?page=' + this.page)
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>