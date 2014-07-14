$(function()
{
    var trainings = new app.collections.Training();
    var vent = _.extend({}, Backbone.Events);

    trainings.reset(Global.data.trainings);

    var trainingsListView = new app.views.TrainingList({ collection: trainings, user_id: Global.data.user_id, vent: vent });

});