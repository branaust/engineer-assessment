import { NativeTabs } from "expo-router/unstable-native-tabs";

import { SearchModeProvider } from "@/src/context/SearchModeContext";

export default function TabLayout() {
  return (
    <SearchModeProvider>
    <NativeTabs tintColor="#0AB463">
      <NativeTabs.Trigger name="index">
        <NativeTabs.Trigger.Icon
          sf={{ default: "person.2", selected: "person.2.fill" }}
          md="people"
        />
        <NativeTabs.Trigger.Label>Characters</NativeTabs.Trigger.Label>
      </NativeTabs.Trigger>

      <NativeTabs.Trigger name="films">
        <NativeTabs.Trigger.Icon
          sf={{ default: "film", selected: "film.fill" }}
          md="movie"
        />
        <NativeTabs.Trigger.Label>Films</NativeTabs.Trigger.Label>
      </NativeTabs.Trigger>

      <NativeTabs.Trigger name="statistics">
        <NativeTabs.Trigger.Icon
          sf={{ default: "chart.bar", selected: "chart.bar.fill" }}
          md="bar_chart"
        />
        <NativeTabs.Trigger.Label>Statistics</NativeTabs.Trigger.Label>
      </NativeTabs.Trigger>
      <NativeTabs.Trigger name="search" role="search">
        <NativeTabs.Trigger.Label>Search</NativeTabs.Trigger.Label>
      </NativeTabs.Trigger>
    </NativeTabs>
    </SearchModeProvider>
  );
}
