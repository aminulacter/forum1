<template>
    <ais-instant-search 
        :search-client="searchClient" 
        index-name="threads"
        
        
        hitsPerPage=8>
          
          <div class="container">
              <div class="row">
                  <div class="col-md-8">
                      <ais-search-box />
                      <!-- <ais-hits-per-page
                    :items="[
                        { label: '8 hits per page', value: 8, default: true },
                        { label: '16 hits per page', value: 16 },
                    ]"
                    /> -->

              <ais-hits>
                  <div slot="item" slot-scope="{ item }">
                      <h2><a :href="item.path">{{ item.title }}</a></h2>
                  </div>
              </ais-hits>
              <ais-pagination />

              </div>
                   <div class="col-md-4">
              <ais-refinement-list attribute="channel.name" />

             
            <div class="card" v-if="trending.length">
                    <div class="card-header">
                        Trending Threads
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                               
                                <li class="list-group-item" v-for="thread in trending" :key="thread.id">
                                     <a :href="thread.path">
                                             {{ thread.title }}
                                     </a>
                                    
                                 </li>
                           
                        </ul>
                       
                    </div>
            </div>
         
          </div>
          
          
          </div>
              </div>
             
          
          
        </ais-instant-search>
</template>
<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

export default {
    props:['trending','question'],
   mounted(){
       console.log(this.trending)
   },
  data() {
    return {
      searchClient: algoliasearch(
        'KV0GIHZYKT',
        '025067582a7f293c59f100a29754b937'
       
      ),
    };
  },
};
</script>

<style lang="scss" scoped>

</style>