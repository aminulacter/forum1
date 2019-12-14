<template>
  <ais-instant-search 
  :search-client="searchClient" 
  index-name="threads">
    <ais-search-box />
    <div class="container">
        <div class="row">
            <div class="col-md-10">
        <ais-hits>
            <div slot="item" slot-scope="{ item }">
                <h2><a :href="item.path">{{ item.title }}</a></h2>
            </div>
        </ais-hits>
        </div>
             <div class="col-md-2">
        <ais-refinement-list attribute="channel.name" />
    </div>
    
    
    </div>
        </div>
       
    
    
  </ais-instant-search>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';

export default {
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

<style>
body {
  font-family: sans-serif;
  padding: 1em;
}
</style>
