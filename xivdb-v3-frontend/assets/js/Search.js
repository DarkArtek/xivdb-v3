import ButtonLoader from './ButtonLoader';

const SearchSettings =
{
    //Url: 'http://api.xivdb-staging.com/Search',
    Url: 'http://api.xivdb.local/Search',
    Delay: 300,
};
const SearchInstance =
{
    LastSearchTerm: null,
    LastSearchPage: null,

    InfiniteScrollCheck: true,
    InputTimeout: null,
    RequestId: null,
    EnterActive: false,
    AllowMoreResults: false,
    Page: 1,
};
const SearchViews =
{
    achievement: data => {
        return `
            ${data['AchievementCategory.Name']} ${data['AchievementCategory.AchievementKind.Name']}
        `
    },

    action: data => {
        return `
            ${data.ClassJobLevel ? data.ClassJobLevel : ''} 
            ${data['ClassJobCategory.Name'] ? data['ClassJobCategory.Name'] : 'System'} 
            ${data['ActionCategory.Name']}
        `;
    },

    bnpcname: data => {
        return ``;
    },

    companion: data => {
        return `
            ${data['Behaviour.Name'] ? data['Behaviour.Name'] : ''}
            ${data['MinionRace.Name'] ? data['MinionRace.Name'] : ''}
        `;
    },

    emote: data => {
        return `
            ${data['TextCommand.Command']} ${data['EmoteCategory.Name']}
        `;
    },

    enpcresident: data => {
        return `${data.Title}`
    },

    fate: data => {
        return `
            Lv: ${data.ClassJobLevel} - ${data.ClassJobLevelMax}
        `;
    },

    instancecontent: data => {
        return `
            ${data['ContentType.Name']}<br>
            Sync: ${data['ContentFinderCondition.ClassJobLevelSync']}
            &nbsp;&nbsp;&nbsp;
            Ilv: ${ data['ContentFinderCondition.ItemLevelSync'] < 1 ? '-' : (data['ContentFinderCondition.ItemLevelRequired'] +'-'+ data['ContentFinderCondition.ItemLevelSync']) } 
        `
    },

    item: data => {
        return `
            ${data['ItemUICategory.Name']}<br>
            ${data['ItemSearchCategory.Name'] ? data['ItemSearchCategory.Name'] : ''}
            ${data['ClassJobCategory.Name'] ? data['LevelEquip'] +' '+ data['ClassJobCategory.Name'] : '' }
        `;
    },

    leve: data => {
        return `
            ${data['JournalGenre.JournalCategory.Name']}<br>
            ${data['ClassJobCategory.Name']} - ${data['JournalGenre.Name']}
        `;
    },

    mount: data => {
        return ``;
    },

    placename: data => {
        return ``;
    },

    quest: data => {
        return `
            ${data['JournalGenre.JournalCategory.Name']}<br>
            ${data['JournalGenre.Name']}
        `;
    },

    recipe: data => {
        return `
            ${data['ClassJob.Name']}
            ${data['SecretRecipeBook.Name'] ? '<br>' + data['SecretRecipeBook.Name'] : ''}
        `;
    },

    status: data => {
        return ``;
    },

    title: data => {
        return `
            ♀ ${data.NameFemale}
        `;
    },

    weather: data => {
        return `
            ${data.Description}
        `;
    },
};

export default class Search
{
    static init()
    {
        const $input = $('.h-search-bar input');

        $input.on('keydown', event => {
            //clearTimeout(SearchInstance.InputTimeout);
        });

        $input.on('keyup', event => {
            //clearTimeout(SearchInstance.InputTimeout);

            // pressed enter, search immediately
            if (!SearchInstance.EnterActive && event.keyCode === 13) {
                this.search();
                return;
            }

            // reset paging stuff
            SearchInstance.LastSearchPage = null;
            SearchInstance.Page = 1;

            // normal routine
            /*
            SearchInstance.EnterActive = false;
            SearchInstance.InputTimeout = setTimeout(() => {
                this.search();
            }, SearchSettings.Delay);
            */
        });

        $('.sea-form').on('submit', event => {
            event.preventDefault();

            SearchInstance.EnterActive = false;
            this.search();
        });

        $('.sea-more button').on('click', event => {
            if (SearchInstance.AllowMoreResults) {
                SearchInstance.Page++;
                this.search();
            }
        });

        // todo - this should be for only logged in members
        $(window).on('scroll', event => {
            if (!SearchInstance.InfiniteScrollCheck || !SearchInstance.AllowMoreResults) {
                return;
            }

            const $button = $('.sea-more > button');
            const top_of_element    = $button.offset().top;
            const bottom_of_element = $button.offset().top + $button.outerHeight();
            const bottom_of_screen  = $(window).scrollTop() + window.innerHeight;
            const top_of_screen     = $(window).scrollTop();

            if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
                SearchInstance.InfiniteScrollCheck = false;
                SearchInstance.Page++;
                ButtonLoader.on($('.sea-more button'), 'light');
                this.search();
            }
        });

        // switch between filters+settings
        $('.sea-menu button').on('click', event => {
            const menu = $(event.target).attr('data-menu');

            $('.sea-block').removeClass('on');
            $(`.sea-${menu}`).addClass('on');
            $('.sea-menu .btn-primary').removeClass('btn-primary').addClass('btn-secondary');
            $(event.target).removeClass('btn-secondary').addClass('btn-primary');
        });
    }

    static search()
    {
        const $input = $('.h-search-bar input');
        const req = {
            string: $input.val().trim(),
            string_algo: $('#searchOptStringAlgo').val().trim(),
            page: SearchInstance.Page,
        };

        // don't do the same search again
        if (req.string === SearchInstance.LastSearchTerm && req.page === SearchInstance.LastSearchPage) {
            return;
        }

        SearchInstance.LastSearchTerm = req.string;
        SearchInstance.LastSearchPage = req.page;

        // always set false so it isn't fired during search
        SearchInstance.AllowMoreResults = false;
        SearchInstance.EnterActive = true;
        SearchInstance.RequestId = Math.floor((Math.random() * 9999999) + 1);

        this.request(SearchInstance.RequestId, req);
    }

    static request(reqId, reqData)
    {
        if (reqData.string.length > 0) {
            $('.sea').addClass('on');
        } else {
            $('.sea').removeClass('on');
            return;
        }

        $('.sea-load').addClass('sea-load-on');

        $.ajax({
            url: SearchSettings.Url,
            data: reqData,
            success: response => {
                // only handle response if the most recent request is the response
                if (reqId === SearchInstance.RequestId) {
                    this.render(response);
                }
            },
            error: (a,b,c) => {
                this.error(a,b,c);
            },
            complete: () => {
                SearchInstance.EnterActive = false;
                $('.sea-load').removeClass('sea-load-on');
                ButtonLoader.off($('.sea-more button'));
            }
        })
    }

    static render(res)
    {
        $('.sea-top .sea-results').text(res.pagination.results_total.toLocaleString('us'));
        $('.sea-top .sea-pages').text(res.pagination.page_total.toLocaleString('us'));
        $('.sea-top .sea-ms span').text(res.ms);

        const $ui = $('.sea-res');

        // first page? empty results
        if (SearchInstance.Page === 1) {
            $ui.html('');

            // scroll to top
            window.scrollTo(0,0);
        }

        if (SearchInstance.Page > 1) {
            $ui.append(`
                <div class="sea-page">Page: ${SearchInstance.Page}</div>
            `);
        }

        // if no results
        if (SearchInstance.Page === 1 && res.pagination.results === 0) {
            $ui.html(`
                <div class="alert alert-secondary">
                <h2>Oh no!?</h2>
                No results for the specified search term or filters
                </div>
            `);
        }

        res.results.forEach(content => {
            if (typeof SearchViews[content._] === 'undefined') {
                console.log('No view for: ', content._, content);
                return;
            }

            // add level display
            let contentFigure = null;
            contentFigure = content.LevelItem ? content.LevelItem : contentFigure;
            contentFigure = content.ClassJobLevel ? content.ClassJobLevel : contentFigure;
            contentFigure = content.ClassJobLevel0 ? content.ClassJobLevel0 : contentFigure;
            contentFigure = content['RecipeLevelTable.ClassJobLevel'] ? content['RecipeLevelTable.ClassJobLevel'] : contentFigure;
            contentFigure = content['Points'] ? content['Points'] : contentFigure;
            contentFigure = content['ContentFinderCondition.ClassJobLevelRequired'] ? content['ContentFinderCondition.ClassJobLevelRequired'] : contentFigure;

            $ui.append(`
                <div class="sea-res-row sea-res-${content.GameType}">
                    <div>
                        <img src="${content.Icon}" class="sea-img-1">
                        <img src="${content.Icon}" class="sea-img-2">
                    </div>
                    <div>
                        <a href="${content.SiteUrl.toLowerCase()}">
                            ${contentFigure ? `<em>${contentFigure}</em>` : ''}
                            <span>${content.Name}</span>
                        </a>
                        <small>(${content.GameType}) ${SearchViews[content._](content).trim()}</small>
                    </div>
                </div>
            `);
        });

        console.log(res);

        // handle more button
        $('.sea-more')[res.pagination.page_next ? 'removeClass' : 'addClass']('off');
        SearchInstance.AllowMoreResults = res.pagination.page_next > 0;
        SearchInstance.InfiniteScrollCheck = res.pagination.page_next > 0;

    }

    static error(a,b,c)
    {
        const $ui = $('.sea-res');
        $ui.html(`
            <div class="alert alert-danger">
                <h2>Oh no!?</h2>
                <p>${c}</p>
            </div>
        `);
    }
}
