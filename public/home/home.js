var initialRequests = [
	'/api/actors',
	'/api/categories',
	'/api/languages',
];

Promise.all(
	initialRequests
		.map(req => fetch(location.origin + req)
		.then(response => response.json()))
)
.then(buildDataLists)
.then(() => {
	$('form input[type=submit]').removeAttr('disabled')
})
.catch(e => {throw new Error(e)});

function buildDataLists(data) {
	const [actors, categories, languages] = data;

	buildOneList(actors, 'actors');
	buildOneList(categories, 'categories');
	buildOneList(languages, 'languages');

	$('form').submit();

	function buildOneList(list, label) {
		const container = $(`form datalist#${label}_list`);
		const optionsFragment = $(document.createDocumentFragment());
		list.forEach(item => {
			$('<option>', {
				'data-id': item.id,
				value: item.name
			}).appendTo(optionsFragment);
		});
		container.append(optionsFragment);
	}
}

$('form').submit(function(event) {
	event.preventDefault();

	const searchCriteriaPossibleLabels = ['title', 'description', 'category', 'actor', 'language'];
	const searchCriteria = {};
	searchCriteriaPossibleLabels.forEach(label => {
		console.log($(event.target).find(`input[name=${label}]:not([value=""])`));
		const input = $(event.target).find(`input[name=${label}]:not([value=""])`);
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
			location.origin + '/api/films?' + buildQueryString(searchCriteria),
			location.origin + `/api/films/log?limit=${limit}&offset=${offset}`
		].map(
			req => fetch(req).then(response => response.json())
		)
	)
	.then(data => {
		const [films, logs] = data;
		buildSearchResultsTable(films);
		buildSearchLogTable(logs)
	})

	function buildQueryString(paramsObj) {
		return Object.keys(paramsObj).map(k => k + '=' + paramsObj[k]).join('&');
	}
});

function buildSearchResultsTable(films) {
	const container = $('#search_results tbody');
	buildTable(films, container);
}

function buildSearchLogTable(logs) {
	const container = $('#search_log tbody');
	buildTable(logs, container);
}

function buildTable(array, tbody) {
	const tbodyFragment = $(document.createDocumentFragment());
	array.forEach(film => {
		const row = $('<tr>', {'data-id': film.id});

		Object.keys(film).forEach(k => {
			$('<td>', {
				text: film[k],
			}).appendTo(row);
		})
		tbodyFragment.append(row)
	});
	tbody.append(tbodyFragment);
}