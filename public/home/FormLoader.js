export class FormLoader {

	/**
	 * gets requests types and prefixes for api loading
	 *
	 * @param      {array of strings}  initialRequests
	 * @param      {string}  apiPrefix
	 */
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

	/**
	 * Builds datalists with autocomplete for actors, categories, languages
	 *
	 * @param     array   data    Promise.all respoonse with all the apis data
	 */
	buildDataLists(data) {
		/**** Fixing tight coupling ****/
		this.initialRequests.forEach((reqName, i) => {
			this._buildOneList(data[i], reqName);
		});

		// const [actors, categories, languages] = data;

		// this._buildOneList(actors, 'actors');
		// this._buildOneList(categories, 'categories');
		// this._buildOneList(languages, 'languages');

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