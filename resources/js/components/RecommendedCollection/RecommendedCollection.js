import React, { useEffect, useState, useCallback } from 'react';
import WidgetCollection from '../WidgetCollection';
import Loader from '../Loader';

import { useRequestedCollection } from '../../utils/hooks';

const RecommendedCollection = ({ id }) => {
	const { items, initial, error, requested } = useRequestedCollection(`/api/v1/recs_for_user_id/${id}`, 10, id);

	const placeholder =  error ? error : ( requested ? 'К сожалению для вас не найдены рекоммендованные книги' : 'Для отображения рекоммендованных для вас книг пожалуйста укажите ID читателя' );
	const title = 'Рекоммендации';

	return (
		<>
			<WidgetCollection
				title={title}
				items={items}
				child='Book'
				placeholder={initial ? <span className='placeholder'>{ placeholder }</span> : <Loader />}
			/>
		</>
	);
};

export default RecommendedCollection;
