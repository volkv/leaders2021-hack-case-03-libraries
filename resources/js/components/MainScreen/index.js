import React, { useState } from 'react';
import WidgetCollection from '../WidgetCollection';
import RecommendedCollection from '../RecommendedCollection';

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
			<RecommendedCollection id={id} />
			{/*<WidgetCollection title="История" items={[1,2,3,4]} />*/}
			{/*<WidgetCollection title="Бестселлеры" items={[1,2,3]} />*/}
		</div>
	);
};

export default MainScreen;