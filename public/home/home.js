import {FormLoader} from './FormLoader.js'
import {FilmSearch} from './FilmSearch.js'

const initialRequests = [
	'actors',
	'categories',
	'languages',
];
const apiPrefix = '/api/';
const formLoader = new FormLoader(initialRequests, apiPrefix);
formLoader.loadInitialData();

const filmSearch = new FilmSearch($('form'));