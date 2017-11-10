define(function() {
    /*
     * Hack to have jsGrid remember the active ordering when
     * filtering: search resets the paging and the sort order and then
     * calls loadData. Since we don't want to reset the sort order
     * when a filter changes, we override search with our own version
     * which resets only the pager and calls loadData.
     */
    jsGrid.Grid.prototype.search = function(filter) {
        this._resetPager();
        return this.loadData(filter);
    };
});
