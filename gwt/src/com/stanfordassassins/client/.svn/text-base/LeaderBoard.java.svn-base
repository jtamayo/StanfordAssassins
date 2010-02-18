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
import com.google.gwt.event.dom.client.ChangeEvent;
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
import com.google.gwt.user.client.ui.Grid;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.HTMLTable;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.Widget;
import com.google.gwt.user.client.ui.HorizontalPanel;

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
	ListBox leaderboardOptionsListBox;
	@UiField
	FlexTable leaderboard;
	@UiField
	FlexTable leaderboardAssassinations;
	@UiField
	FlexTable leaderboardGamesPlayed;
	@UiField
	FlexTable leaderboardGamesWon;
	@UiField
	Label leaderboardTitle;
	
	StanfordAssassins controller ;
	
	public LeaderBoard(StanfordAssassins controller) {
		initWidget(uiBinder.createAndBindUi(this));
		this.controller = controller;
		
		for (LeaderboardOption item : LeaderboardOption.values()) {
			leaderboardOptionsListBox.addItem(item.toString(), item.name());
		}
		leaderboard.setVisible(true);
		leaderboardAssassinations.setVisible(false);
		leaderboardGamesPlayed.setVisible(false);
		leaderboardGamesWon.setVisible(false);
		leaderboardTitle.setText(LeaderboardOption.GENERAL.toString());
	}
	

	@UiHandler("leaderboardOptionsListBox")
	void onChange(ChangeEvent e) {
		LeaderboardOption option = LeaderboardOption.valueOf(leaderboardOptionsListBox.getValue(leaderboardOptionsListBox.getSelectedIndex()));
		leaderboardTitle.setText(option.toString());
		switch(option){
			case ASSASSINATIONS:
				leaderboard.setVisible(false);
				leaderboardAssassinations.setVisible(true);
				leaderboardGamesPlayed.setVisible(false);
				leaderboardGamesWon.setVisible(false);
				break;
			case GAMESPLAYED:
				leaderboard.setVisible(false);
				leaderboardAssassinations.setVisible(false);
				leaderboardGamesPlayed.setVisible(true);
				leaderboardGamesWon.setVisible(false);
				break;
			case GAMESWON:
				leaderboard.setVisible(false);
				leaderboardAssassinations.setVisible(false);
				leaderboardGamesPlayed.setVisible(false);
				leaderboardGamesWon.setVisible(true);
				break;
			case GENERAL:
				leaderboard.setVisible(true);
				leaderboardAssassinations.setVisible(false);
				leaderboardGamesPlayed.setVisible(false);
				leaderboardGamesWon.setVisible(false);
				break;
		}
	}
	
	
	public void update(JsArray<PlayerStats> stats,int currentPlayerId){
		updateLeaderBoard(stats,currentPlayerId);
		updateAssassinations(stats,currentPlayerId);
		updateGamesPlayed(stats,currentPlayerId);
		updateGamesWon(stats,currentPlayerId);
	}
	private void updateLeaderBoard(JsArray<PlayerStats> stats,int currentPlayerId) {
		leaderboard.clear();
		List<PlayerStats> l = new ArrayList<PlayerStats>();
		for (int i = 0; i< stats.length() ; i++) { 
			l.add(stats.get(i));
		}
		Collections.sort(l,new Comparator<PlayerStats>() {
			public int compare(PlayerStats o1, PlayerStats o2) {
				if(leaderBoardScore(o1) < leaderBoardScore(o2)) return -1;
				else if(leaderBoardScore(o1) == leaderBoardScore(o2)) return 0;
				else return 1 ;
			}
		});
		
		int index = 0 ;
		for (int i = 0; i< l.size() ; i++)  {
			if (l.get(i).getPlayerId() == currentPlayerId) {
				index = i ;
				break;
			}
				
		}
		for (int i = 0; i< l.size() ; i++)  {
			PlayerStats stat = l.get(i) ;
			if (i>l.size()-10 || (i>index-10 && i<index+10))
				addRowLeaderBoard(l.size() -i,stat.getName(),(float)Math.round(leaderBoardScore(stat)*100)/100,currentPlayerId == stat.getPlayerId());
		}
		leaderboard.insertRow(0);
		HTML html = new HTML( "Rank") ;
		html.getElement().setClassName("leaderBoardHeader");
		leaderboard.setWidget(0, 0, html);
		
		html = new HTML( "Player Name");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboard.setWidget(0, 1, html);
		
		html = new HTML("Score");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboard.setWidget(0, 2, html);
	}
	
	private float leaderBoardScore(PlayerStats stats){
		if (stats.getGamesPlayed() == 0 )
			return 0 ;
		else
			return ((float)stats.getAssassinations()/stats.getGamesPlayed())+((float)stats.getGamesWon()/stats.getGamesPlayed());
	}
	
	private void updateAssassinations(JsArray<PlayerStats> stats,int currentPlayerId) {
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
		
		int index = 0 ;
		for (int i = 0; i< l.size() ; i++)  {
			if (l.get(i).getPlayerId() == currentPlayerId) {
				index = i ;
				break;
			}
				
		}
		for (int i = 0; i< l.size() ; i++)  {
			PlayerStats stat = l.get(i) ;
			if (i>l.size()-10 || (i>index-10 && i<index+10))
				addRowAssassinations(l.size()-i,stat.getName(),stat.getAssassinations(),currentPlayerId == stat.getPlayerId());
		}
		
		leaderboardAssassinations.insertRow(0);
		HTML html = new HTML( "Rank") ;
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardAssassinations.setWidget(0, 0, html);
		
		html = new HTML( "Player Name");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardAssassinations.setWidget(0, 1, html);
		
		html = new HTML("Score");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardAssassinations.setWidget(0, 2, html);
	}
	
	private void updateGamesPlayed(JsArray<PlayerStats> stats, int currentPlayerId) {
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
		int index = 0 ;
		for (int i = 0; i< l.size() ; i++)  {
			if (l.get(i).getPlayerId() == currentPlayerId) {
				index = i ;
				break;
			}
				
		}
		for (int i = 0; i< l.size() ; i++)  {
			PlayerStats stat = l.get(i) ;
			if (i>l.size()-10 || (i>index-10 && i<index+10))
				addRowGamesPlayed(l.size()-i,stat.getName(),stat.getGamesPlayed(),currentPlayerId == stat.getPlayerId());
		}
		leaderboardGamesPlayed.insertRow(0);
		HTML html = new HTML( "Rank") ;
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesPlayed.setWidget(0, 0, html);
		
		html = new HTML( "Player Name");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesPlayed.setWidget(0, 1, html);
		
		html = new HTML("Score");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesPlayed.setWidget(0, 2, html);
	}
	
	private void updateGamesWon(JsArray<PlayerStats> stats, int currentPlayerId) {
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
		int index = 0 ;
		for (int i = 0; i< l.size() ; i++)  {
			if (l.get(i).getPlayerId() == currentPlayerId) {
				index = i ;
				break;
			}
				
		}		
		for (int i = 0; i< l.size() ; i++)  {
			PlayerStats stat = l.get(i) ;
			if (i>l.size()-10 || (i>index-10 && i<index+10))
				addRowGamesWon(l.size()-i,stat.getName(),stat.getGamesWon(),currentPlayerId == stat.getPlayerId());
		}
		leaderboardGamesWon.insertRow(0);
		HTML html = new HTML( "Rank") ;
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesWon.setWidget(0, 0, html);
		
		html = new HTML( "Player Name");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesWon.setWidget(0, 1, html);
		
		html = new HTML("Score");
		html.getElement().setClassName("leaderBoardHeader");
		leaderboardGamesWon.setWidget(0, 2, html);
	}
	
	private void addRowLeaderBoard(int rankingIndex,String player, float score,boolean isCurrentUser) {
		leaderboard.insertRow(0);
		HTML html = new HTML( "" + rankingIndex  +"") ;
		html.getElement().setClassName("leaderBoardRankingColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboard.setWidget(0, 0, html);
		
		html = new HTML( "" + player);
		html.getElement().setClassName("leaderBoardNameColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboard.setWidget(0, 1, html);
		
		html = new HTML(""+score);
		html.getElement().setClassName("leaderBoardValueColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboard.setWidget(0, 2, html);
	}
	
	private void addRowAssassinations(int rankingIndex,String player, int assassinations,boolean isCurrentUser) {
		leaderboardAssassinations.insertRow(0);
		HTML html = new HTML( "" + rankingIndex  +"") ;
		html.getElement().setClassName("leaderBoardRankingColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardAssassinations.setWidget(0, 0, html);
		
		html = new HTML( "" + player);
		html.getElement().setClassName("leaderBoardNameColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardAssassinations.setWidget(0, 1, html);
		
		html = new HTML(""+assassinations);
		html.getElement().setClassName("leaderBoardValueColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardAssassinations.setWidget(0, 2, html);
	}
	private void addRowGamesPlayed(int rankingIndex,String player, int gamesPlayed,boolean isCurrentUser) {
		leaderboardGamesPlayed.insertRow(0);
		
		HTML html = new HTML( "" + rankingIndex  +"") ;
		html.getElement().setClassName("leaderBoardRankingColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesPlayed.setWidget(0, 0, html);
		
		html = new HTML( "" + player);
		html.getElement().setClassName("leaderBoardNameColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesPlayed.setWidget(0, 1, html);
		
		html = new HTML(""+gamesPlayed);
		html.getElement().setClassName("leaderBoardValueColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesPlayed.setWidget(0, 2, html);
	}
	private void addRowGamesWon(int rankingIndex,String player, int gamesWon,boolean isCurrentUser) {
		leaderboardGamesWon.insertRow(0);
		HTML html = new HTML( "" + rankingIndex  +"") ;
		html.getElement().setClassName("leaderBoardRankingColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesWon.setWidget(0, 0, html);
		
		html = new HTML( "" + player);
		html.getElement().setClassName("leaderBoardNameColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesWon.setWidget(0, 1, html);
		
		html = new HTML(""+gamesWon);
		html.getElement().setClassName("leaderBoardValueColumn");
		if (isCurrentUser)
			html.getElement().addClassName("currentUserleaderBoardRow"); 
		leaderboardGamesWon.setWidget(0, 2, html);
	}
}
