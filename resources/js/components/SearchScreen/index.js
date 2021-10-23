import React, { useState } from 'react';
import WidgetCollection from '../WidgetCollection';
import Loader from '../Loader';

import { useRequestedCollection } from '../../utils/hooks';

const SearchScreen = ({ match }) => {
	const searchQuery = match.params?.query ?? '';
	const { items, initial, error, requested } = useRequestedCollection(`/api/v1/search?q=${searchQuery}`, 20, searchQuery);

	const placeholder =  error ? error : ( requested ? 'К сожалению по вашему запросу ничего не найден' : 'Для поиска заполните поле ввода выше' );
	const title = `Поиск по запросу '${searchQuery}'`;


	return (
		<div>
			<WidgetCollection
				title={title}
				items={items}
				child='Book'
				placeholder={initial ? <span className='placeholder'>{ placeholder }</span> : <Loader />} 
			/>
		</div>
	);
};

export default SearchScreen;