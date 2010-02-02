/**
 * 
 */
package com.stanfordassassins.client;

import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DisclosurePanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Andreas
 *
 */
public class LeaderBoard extends Composite {

	private static LeaderBoardUiBinder uiBinder = GWT
			.create(LeaderBoardUiBinder.class);

	interface LeaderBoardUiBinder extends UiBinder<Widget, LeaderBoard> {
	}

	@UiField
	FlexTable leaderboardAssassinations;
	@UiField
	FlexTable leaderboardGamesPlayed;
	@UiField
	FlexTable leaderboardGamesWon;
	
	public LeaderBoard() {
		initWidget(uiBinder.createAndBindUi(this));
		
	}
	
	public void update(JsArray<PlayerStats> stats){
		updateAssassinations(stats);
		updateGamesPlayed(stats);
		updateGamesWon(stats);
	}
	private void updateAssassinations(JsArray<PlayerStats> stats) {
		leaderboardAssassinations.clear();
		List<PlayerStats> l = new ArrayList<PlayerStats>();
		for (int i = 0; i< stats.length() ; i++) { 
			l.add(stats.get(i));
		}
		Collections.sort(l,new Comparator<PlayerStats>() {
			public int compare(PlayerStats o1, PlayerStats o2) {
				if(o1.getAssassinations() < o2.getAssassinations())return -1;
				else if(o1.getAssassinations() == o2.getAssassinations()) return 0;
				else return 1 ;
			}
		});
		
		for (PlayerStats stat:l) { 
			addRowAssassinations(stat.getName(),stat.getAssassinations());
		}
	}
	
	private void updateGamesPlayed(JsArray<PlayerStats> stats) {
		leaderboardGamesPlayed.clear();
		List<PlayerStats> l = new ArrayList<PlayerStats>();
		for (int i = 0; i< stats.length() ; i++) { 
			l.add(stats.get(i));
		}
		Collections.sort(l,new Comparator<PlayerStats>() {
			public int compare(PlayerStats o1, PlayerStats o2) {
				if(o1.getGamesPlayed() < o2.getGamesPlayed())return -1;
				else if(o1.getGamesPlayed() == o2.getGamesPlayed()) return 0;
				else return 1 ;
			}
		});
		
		for (PlayerStats stat:l) { 
			addRowGamesPlayed(stat.getName(),stat.getGamesPlayed());
		}
	}
	
	private void updateGamesWon(JsArray<PlayerStats> stats) {
		leaderboardGamesWon.clear();
		List<PlayerStats> l = new ArrayList<PlayerStats>();
		for (int i = 0; i< stats.length() ; i++) { 
			l.add(stats.get(i));
		}
		Collections.sort(l,new Comparator<PlayerStats>() {
			public int compare(PlayerStats o1, PlayerStats o2) {
				if(o1.getGamesWon() < o2.getGamesWon())return -1;
				else if(o1.getGamesWon() == o2.getGamesWon()) return 0;
				else return 1 ;
			}
		});
		
		for (PlayerStats stat:l) { 
			addRowGamesWon(stat.getName(),stat.getGamesWon());
		}
	}
	
	private void addRowAssassinations(String player, int assassinations) {
		leaderboardAssassinations.insertRow(0);
		FlowPanel panel = new FlowPanel();
		panel.add(new HTML( "<b>" + player +"</b>:"));
		panel.add(new Label(""+assassinations));
		leaderboardAssassinations.setWidget(0, 0, panel);
	}
	private void addRowGamesPlayed(String player, int gamesPlayed) {
		leaderboardGamesPlayed.insertRow(0);
		FlowPanel panel = new FlowPanel();
		panel.add(new HTML( "<b>" + player +"</b>:"));
		panel.add(new Label(""+gamesPlayed));
		leaderboardGamesPlayed.setWidget(0, 0, panel);
	}
	private void addRowGamesWon(String player, int gamesWon) {
		leaderboardGamesWon.insertRow(0);
		FlowPanel panel = new FlowPanel();
		panel.add(new HTML( "<b>" + player +"</b>:"));
		panel.add(new Label(""+gamesWon));
		leaderboardGamesWon.setWidget(0, 0, panel);
	}
}
