import {CustomString} from './CustomString.js'
import {CustomUrl} from './CustomUrl.js'

export class FilmSearch {
	constructor (form) {
		this.form = form;
		this.form.find('input[type=search]')
			.on('change keyup', this.loadFilmsOnKeyup.bind(this));
	}

	loadFilmsOnKeyup (event) {
		const searchCriteriaPossibleLabels = ['title', 'description', 'category', 'actor', 'language'];
		const searchCriteria = {};
		searchCriteriaPossibleLabels.forEach(label => {
			const input = this.form.find(`input[name=${label}]:not([value=""])`);
			if (input.length) {
				searchCriteria[label] = input.val();
			}
		});

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
		const tbodyFragment = $(document.createDocumentFragment());
		array.forEach(film => {
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