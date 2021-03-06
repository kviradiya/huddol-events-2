TaxonomyTranslation = {};
TaxonomyTranslation.classes = {
    instantiatedTermModels : {}
};
TaxonomyTranslation.models = {};
TaxonomyTranslation.collections = {};
TaxonomyTranslation.views = {};
TaxonomyTranslation.mainView = {};
TaxonomyTranslation.mainView.filterView = {};
TaxonomyTranslation.data = {};
TaxonomyTranslation.data.translatedTaxonomyLabels = {};
TaxonomyTranslation.data.compiledTemplates = {};


(function () {
    TaxonomyTranslation.data.Templates = {
        main: [
            "<label for=\"icl_tt_tax_switch\">",
                "<%=labels.taxToTranslate%>",
                "<select id=\"icl_tt_tax_switch\">",
                    "<option disabled selected> -- <%=labels.taxonomy%> --</option>",
                    "<% _.each(taxonomies, function(taxonomy, index){ %>",
                        "<option value=\"<%=index%>\">",
                            "<%=taxonomy.label%>",
                        "</option>",
                "<% }); %>",
                "</select>",
            "</label>",
            "<div style=\"display:none;\"><span class=\"spinner loading-taxonomy\" style=\"float:left;\"></span><%=labels.preparingTermsData%></div>",
            "<div id=\"taxonomy-translation\">",
            "</div>"],
        taxonomy: [
                "<h3 id=\"term-table-header\"><%=headerTerms%></h3>",
                "<p id=\"term-table-summary\"><%=summaryTerms%></p>",
                "<div id=\"wpml-taxonomy-translation-filters\"></div>",
                "<div id=\"wpml-taxonomy-translation-terms-table\"></div>",
                "<div id=\"wpml-taxonomy-translation-terms-nav\"></div>",
                "<p id=\"term-label-summary\"><%=labelSummary%></p>",
                "<div id=\"wpml-taxonomy-translation-labels-table\"></div>"
            ],
        filter: ["<div class=\"icl-tt-tools\">",
                    "<label for=\"status-select\"><%=labels.Show%></label>  <select id=\"status-select\" name=\"status\">",
                        "<option value=\"0\"><%=labels.all + ' ' + taxonomy.label%></option>",
                        "<option value=\"1\"><%=labels.untranslated + ' ' + taxonomy.label%></option>",
                    "</select>",
                    "<label for=\"in-lang\" id=\"in-lang-label\" class=\"hidden\"><%=labels.in%></label>",
                       "<select name=\"language\" id=\"in-lang\" class=\"hidden\">",
                        "<option value=\"all\"><%=labels.anyLang%></option>",
                        "<%_.each(langs, function(lang, code){%>",
                            "<option value=\"<%=code%>\"><%=lang.label%></option>",
                        "<%});%>",
                    "</select>",
                    "<input type=\"text\" name=\"search\" id=\"tax-search\" placeholder=\"<%=labels.searchPlaceHolder%>\" value=\"\">",
                    "<% if(taxonomy.hierarchical) {%>",
                        "<select name=\"child_of\" id=\"child_of\" class=\"postform\">",
                            "<option value=\"-1\" selected=\"selected\">???<%=labels.selectParent%>???</option>",
                            "<% _.each(parents, function(parent, term_id){ %>",
                                "<option value=\"<%=term_id%>\">???<%=parent%>???</option>",
                            "<% }); %>",
                        "</select>",
                    "<% } %>",
                    "<input type=\"submit\" class=\"button-primary\" style=\"display:none;\" value=\"<%=labels.apply%>\" id=\"tax-apply\">",
                    "<span class=\"spinner\"></span>",
                "</div>"],
        nav: ["<div class=\"tablenav bottom\">",
                    "<div class=\"tablenav-pages\" id=\"taxonomy-terms-table-nav\">",
                        "<span class=\"displaying-num\">",
                            "<% if(pages > 1) { %>",
                                "<%=items%> <%=labels.items%>",
                            "<% } else if(pages === 1) {%>",
                                "1 <%=labels.item%>",
                            "<% } %>",
                        "</span>",
                        "<a class=\"first-page <% if(page <= 1 ){ %> disabled <% } %>\" href=\"###\"",
                        "title=\"<%=labels.goToFirstPage%>\">??</a>",
                        "<a href=\"###\" title=\"<%=labels.goToPreviousPage%>\"",
                            "class=\"prev-page <% if(page < 2 ) {%> disabled<% } %>\">???</a>",
                        "<input class=\"current-page\" size=\"1\" value=\"<%=page%>\"",
                        "title=\"<%=labels.currentPage%>\" type=\"text\"/>",
                        "<%=labels.of%> <span class=\"total-pages\"><%=pages%></span>",
                        "<a class=\"next-page  <% if(page == pages ) {%> disabled <% } %>\" href=\"###\"",
                            "title=\"<%=labels.goToNextPage%>\">???</a>",
                        "<a class=\"last-page <% if(page == pages ) {%> disabled <% } %>\" href=\"###\"",
                            "title=\"<%=labels.goToLastPage%>\">??</a>",
                    "</div>",
                "</div>"],
        termTranslated: [
            "<a class=\"icl_tt_term_name\" href=\"#\"",
                "id=\"<%=trid + '-' + lang%>\">",
                "<% if(!name){ %>",
                    "<%=labels.lowercaseTranslate%>",
                    "<% } else {  %>",
                        "<%if(level>0){%>",
                            "<%=Array(level+1).join('???') + \" \"%>",
                        "<% } %>",
                    "<%=name%>",
                "<% } %>",
            "</a>",
            "<div id=\"<%=trid + '-popup-' + lang%>\"></div>"
            ],
        termNotTranslated:[
            "<a class=\"icl_tt_term_name lowlight\" href=\"#\"",
            "id=\"<%=trid + '-' + lang%>\">",
                "<%=labels.lowercaseTranslate%>",
            "</a>",
            "<div id=\"<%=trid + '-popup-' + lang%>\"></div>"

        ],
        termPopUp: ["<div class=\"icl_tt_form\" id=\"icl_tt_form_<%=trid + '_' + lang%>\">",
                        "<table class=\"icl_tt_popup\">",
                            "<tbody>",
                                "<tr>",
                                    "<th colspan=\"2\">",
                                        "<%=labels.translate%>",
                                        "<strong><%=originalName%></strong>",
                                        "<%=labels.to + ' ' + langs[lang].label%>",
                                    "</th>",
                                "</tr>",
                                "<tr>",
                                    "<td><label for=\"term-name\"> <%=labels.Name%> </label></td>",
                                    "<td><input id=\"term-name\" value=\"<%=name%>\" type=\"text\"></td>",
                                "</tr>",
                                "<tr>",
                                    "<td><label for=\"term-slug\"><%=labels.Slug%></label></td>",
                                    "<td><input id=\"term-slug\" value=\"<%=slug%>\" type=\"text\"></td>",
                                "</tr>",
                                "<tr>",
                                    "<td><label for=\"term-description\"><%=labels.Description%></label></td>",
                                    "<td><textarea id=\"term-description\" cols=\"22\" rows=\"2\"><%=description%></textarea>",
                                    "</td>",
                                "</tr>",
                                "<tr>",
                                    "<td colspan=\"2\" align=\"right\">",
                                        "<span class=\"errors icl_error_text\"></span>",
                                        "<input class=\"button-secondary cancel\" value=\"<%=labels.cancel%>\" type=\"button\">",
                                        "<input class=\"button-primary term-save\" value=\"<%=labels.Ok%>\" type=\"submit\">",
                                        "<span class=\"spinner\"></span>",
                                    "</td>",
                                "</tr>",
                            "</tbody>",
                        "</table>",
                    "</div>"],
        labelPopUp: [
            "<div class=\"icl_tt_form\" id=\"icl_tt_form_<%=taxonomy%>\">",
                "<table class=\"icl_tt_popup\">",
                    "<tbody>",
                        "<tr>",
                            "<th colspan=\"2\">",
                                "<%=labels.translate%>",
                                "<strong><%=originalS%> / <%=originalP%></strong>",
                                "<%=labels.to + ' ' + langs[lang].label%>",
                            "</th>",
                        "</tr>",
                        "<tr>",
                            "<td><label for=\"<%=taxonomy%>-singular\"> <%=labels.Singular%> </label></td>",
                            "<td><input id=\"<%=taxonomy%>-singular\" value=\"<%=transS%>\" type=\"text\"></td>",
                        "</tr>",
                        "<tr>",
                            "<td><label for=\"<%=taxonomy%>-plural\"><%=labels.Plural%></label></td>",
                            "<td><input id=\"<%=taxonomy%>-plural\" value=\"<%=transP%>\" type=\"text\"></td>",
                        "</tr>",
                        "<tr>",
                            "<td colspan=\"2\" align=\"right\">",
                                "<span class=\"errors icl_error_text\"></span>",
                                "<input class=\"button-secondary cancel\" value=\"<%=labels.cancel%>\" type=\"button\">",
                                "<input class=\"button-primary label-save\" value=\"<%=labels.Ok%>\" type=\"submit\">",
                                "<span class=\"spinner\"></span>",
                            "</td>",
                        "</tr>",
                    "</tbody>",
                "</table>",
            "</div>"],
        labelRow: [
                "<% _.each(langs, function(lang, code){ %>",
                "<%if(!taxLabels[lang]){%>",
                        "<%=TaxonomyTranslation.getTemplate('individualLabel')({taxonomy: taxonomy, label: false, singularLabel: false,lang: lang})%>",
                    "<% } else { %>",
                "<% if(taxLabels[lang].original){ %><%=TaxonomyTranslation.getTemplate('originalLabel')({singularLabel: taxLabels[lang].singular, label: taxLabels[lang].general})%><%} else {%>",
                         "<%= TaxonomyTranslation.getTemplate('individualLabel')({taxonomy: taxonomy, singularLabel: taxLabels[lang].singular, label: taxLabels[lang].general,lang: lang})%>",
                    "<% } } %>",
                "<% }); %>"
            ],
        individualLabel: ["<td><a class=\"icl_tt_label <% if(!label){%> lowlight<%}%>\" id=\"<%=taxonomy%>_<%=lang%>\" href=\"#\"><% if(!label){%>translate<%} else {%><%=singularLabel +' / ' + label %><%}%></a><div id=\"popup-<%=lang%>\"></div></td>"],
        originalLabel: ["<td><span><%=singularLabel +' / ' + label %></span></td>"],
        table: [ "<div class=\"icl_tt_main_top\">",
                    "<table class=\"wp-list-table widefat fixed\" id=\"tax-table-<%=tableType%>\">",
                        "<thead>",
                            "<tr>",
                                "<% _.each(langs, function(lang){ %>",
                                    "<th><%=lang.label%></th>",
                                "<% }); %>",
                            "</tr>",
                        "</thead>",
                    "</table>",
                "</div>"]

    };

    TaxonomyTranslation.getTemplate = function (temp) {

        if (TaxonomyTranslation.data.Templates.hasOwnProperty(temp)) {
            if (TaxonomyTranslation.data.compiledTemplates[temp] === undefined) {
                TaxonomyTranslation.data.compiledTemplates[temp] = _.template(TaxonomyTranslation.data.Templates[temp].join("\n"))
            }
            return TaxonomyTranslation.data.compiledTemplates[temp];
        }
    };

})(TaxonomyTranslation);