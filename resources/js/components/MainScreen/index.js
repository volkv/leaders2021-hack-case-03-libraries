import React, { useState } from 'react';
import WidgetCollection from '../WidgetCollection';
import RecommendedCollection from '../RecommendedCollection';
import NeighboursCollection from '../NeighboursCollection';

const MainScreen = () => {
	const [id, setId] = useState('')
	const changeId = (event) => {
		setId(event.target.value)
	};

	return (
		<div>
			<div className='header'>
				<div className='search-container'>
					<input placeholder='ID читателя' value={id} onChange={changeId} />
				</div>
			</div>
			<RecommendedCollection id={id} child='Book' />
			<NeighboursCollection id={id} child='Person' />
		</div>
	);
};

export default MainScreen;