 /**
  * Model Szkolenia
  *
  */
app.models.Training = Backbone.Model.extend({
    idAttribute: "id",
    url: "/training/training/",

    defaults: {
        id              : "",
        name            : "",
        total_vacancies : "",
        free_vacancies  : "",
        users_cnt       : "",
    },

    parse: function(response)
    {
        if (response.data && response.date)
            return response.data;
        return response;
    }
});


/**
 * Kolekcja szkoleń
 */
app.collections.Training = Backbone.Collection.extend({
    model: app.models.Training,
    url  : "/training/getTrainings/",

    parse: function(response)
    {
        return response;
    }
});

app.views.TrainingList = Backbone.View.extend({
    wrapper_el : "#trainings",
    rows_el : "#training_list",
    template : template("training_list"),

    initialize: function(options)
    {
        this.$wrapper_el = $(this.wrapper_el);
        this.user_id = options.user_id || null;
        this.vent = options.vent;
        this.listenTo(this.vent, "refreshList", this.refreshList);
        this.render();

        $("#search_btn").click(function () {
            var pattern = $("#search_input").val();
            this.search(pattern);
        }.bind(this));

        $('#search_input').keyup(function(e) {
            if(e.keyCode == 13)
            {
                var pattern = $("#search_input").val();
                this.search(pattern);
            }
        }.bind(this));
    },

    /**
     * Generowanie widoku listy szkoleń
     */
    render: function()
    {
        this.$wrapper_el.empty();
        this.$wrapper_el.html(this.template({ empty: this.collection.length == 0 }));
        this.$rows_el = $(this.rows_el);
        this.collection.each(this.renderItem, this);

    },

    refreshList: function()
    {
        var self = this;
        this.collection.fetch(
        {
            success: function(collection)
            {
                self.render();
            }
        });
    },

    search: function(pattern)
    {
        var self = this;
        this.collection.fetch(
        {
            data: {
                search_pattern : pattern
            },
            success: function(collection)
            {
                self.render();
            }
        });
    },

    /**
    * Generowanie pojedynczego wiersza
    */
    renderItem: function(training_model)
    {
        var training_view = new app.views.TrainingView({ model: training_model, user_id: this.user_id, vent: this.vent });
        this.$rows_el.append(training_view.el);

        return training_view;
    }
});

app.views.TrainingView = Backbone.View.extend({
    tagName: "tr",
    template : template("training"),

    events: {
        "click .join_training" : "joinTraining",
        "click .leave_training" : "leaveTraining"
    },

    joinTraining: function()
    {
        var self = this;
        $.ajax({
            url : "/training/joinTraining/",
            type: "POST",
            data: {
                user_id: self.user_id,
                training_id: self.model.get('id')
            },
            success: function(response) {
                self.vent.trigger("refreshList");
            }
        });
    },

    leaveTraining: function()
    {
        var self = this;
        $.ajax({
            url : "/training/leaveTraining/",
            type: "POST",
            data: {
                user_id: self.user_id,
                training_id: self.model.get('id')
            },
            success: function(response) {
                self.vent.trigger("refreshList");
            }
        });
    },

    initialize: function(options)
    {
        this.user_id = options.user_id || null;
        this.vent = options.vent;
        this.render();
    },

    render: function()
    {
        var data = this.model.toJSON();
        this.$el.html(this.template(data));
    },
    destroyView: function()
    {
        this.el.id = "";
    }
});