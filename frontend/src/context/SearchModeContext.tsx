import { createContext, useContext, useState } from "react";

type SearchMode = "people" | "films";

const SearchModeContext = createContext<{
  mode: SearchMode;
  setMode: (mode: SearchMode) => void;
}>({ mode: "people", setMode: () => {} });

export function SearchModeProvider({ children }: { children: React.ReactNode }) {
  const [mode, setMode] = useState<SearchMode>("people");
  return (
    <SearchModeContext.Provider value={{ mode, setMode }}>
      {children}
    </SearchModeContext.Provider>
  );
}

export const useSearchMode = () => useContext(SearchModeContext);
