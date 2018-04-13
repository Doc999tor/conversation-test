export class FormLoader {
	constructor (initialRequests, apiPrefix) {
		this.initialRequests = initialRequests;
		this.apiPrefix = apiPrefix;
	}

	loadInitialData () {
		Promise.all(
			this.initialRequests
				.map(req => fetch(location.origin + this.apiPrefix + req)
				.then(response => response.json()))
		)
		.then(this.buildDataLists.bind(this))
		.then(() => {
			$('form input[type=submit]').removeAttr('disabled');
		})
		.catch(e => {throw new Error(e)});
	}

	buildDataLists(data) {
		const [actors, categories, languages] = data;

		this._buildOneList(actors, 'actors');
		this._buildOneList(categories, 'categories');
		this._buildOneList(languages, 'languages');

		$('form input[name=language]').trigger('change');

	}
	_buildOneList(list, label) {
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