import {CustomString} from './CustomString.js'
import {CustomUrl} from './CustomUrl.js'

export class FilmSearch {
	constructor (form) {
		this.form = form;
	}

	/**
	 * gets events list and register it on input[type=search] of the form
	 *
	 * @param      {string[]}  eventsArr
	 */
	registerEvents (eventsArr) {
		this.form.find('input[type=search]')
			.on(eventsArr.join(' '), this.loadFilmsOnRegisteredEvents.bind(this));
	}

	/**
	 * Loads films on registered events.
	 *
	 * @param      {MouseEvent|FormEvent}  event
	 */
	loadFilmsOnRegisteredEvents (event) {
		/**
		 * list of possible input[type=search] names
		 * @type       {Array}
		 */
		const searchCriteriaPossibleLabels = ['title', 'description', 'category', 'actor', 'language'];
		const searchCriteria = {};
		searchCriteriaPossibleLabels.forEach(label => {
			const input = this.form.find(`input[name=${label}]`);
			if (input.val()) {
				searchCriteria[label] = input.val();
			}
		});

		/**
		 * default limit (page size) and offset ((limit-1) * number of pages)
		 *
		 * @type       {number}
		 */
		const limit = 20;
		const offset = 0;
		searchCriteria['limit'] = limit;
		searchCriteria['offset'] = offset;

		Promise.all(
			[
				location.origin + '/api/films?' + CustomUrl.buildQueryString(searchCriteria),
				location.origin + `/api/films/log?limit=${limit}&offset=${offset}`
			].map(
				req => fetch(req).then(response => response.json())
			)
		)
		.then(data => {
			const [films, logs] = data;
			this.buildSearchResultsTable(films);
			this.buildSearchLogTable(logs)
		});
	}

	buildSearchResultsTable(films) {
		const container = $('#search_results tbody');
		container.empty();
		this.buildTable(films, container);
	}

	buildSearchLogTable(logs) {
		const container = $('#search_log tbody');
		container.empty();
		this.buildTable(logs, container);
	}

	buildTable(array, tbody) {
		/**
		 * documentFragments includes all new tags and appends it once
		 * @type       jquery wrapper of DocumentFragment
		 */
		const tbodyFragment = $(document.createDocumentFragment());
		array.forEach(film => {
			/**
			 * we cut strings for 15 letters (ellipsis), afterwards will be added an option to expend it locally
			 * @type       {number}
			 */
			const maxStrLength = 15;
			const row = $('<tr>', {'data-id': film.id});

			Object.keys(film).forEach(k => {
				if (k === 'id') {return;}
				const text = new CustomString(film[k]);
				$('<td>', {
					text: text.cutLongTexts(maxStrLength),
				}).appendTo(row);
			})
			tbodyFragment.append(row)
		});
		tbody.append(tbodyFragment);
	}
}