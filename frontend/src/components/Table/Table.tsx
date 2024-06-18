import * as React from "react";
import { alpha } from "@mui/material/styles";
import Box from "@mui/material/Box";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableContainer from "@mui/material/TableContainer";
import TableHead from "@mui/material/TableHead";
import TablePagination from "@mui/material/TablePagination";
import TableRow from "@mui/material/TableRow";
import TableSortLabel from "@mui/material/TableSortLabel";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import Paper from "@mui/material/Paper";
import Checkbox from "@mui/material/Checkbox";
import IconButton from "@mui/material/IconButton";
import Tooltip from "@mui/material/Tooltip";
import DeleteIcon from "@mui/icons-material/Delete";
import FilterListIcon from "@mui/icons-material/FilterList";
import { visuallyHidden } from "@mui/utils";
import { Card, MenuItem, Select, TextField } from "@mui/material";
import { Add } from "@mui/icons-material";

type Order = "asc" | "desc";

type FilterType = "like";

interface Data {
  id: number;
  [key: string]: any;
}

interface HeadCell {
  id: keyof Data;
  label: string;
  isOrderable: boolean;
  isFilterable: boolean;
}

interface EnhancedTableHeadProps {
  columns: readonly HeadCell[];
  numSelected: number;
  onRequestSort: (
    event: React.MouseEvent<unknown>,
    property: keyof Data
  ) => void;
  onSelectAllClick: (event: React.ChangeEvent<HTMLInputElement>) => void;
  order: Order;
  orderBy: string;
  rowCount: number;
}

function EnhancedTableHead(props: EnhancedTableHeadProps) {
  const {
    columns,
    onSelectAllClick,
    order,
    orderBy,
    numSelected,
    rowCount,
    onRequestSort,
  } = props;
  const createSortHandler =
    (property: keyof Data) => (event: React.MouseEvent<unknown>) => {
      onRequestSort(event, property);
    };

  return (
    <TableHead>
      <TableRow>
        <TableCell padding="checkbox">
          <Checkbox
            color="primary"
            indeterminate={numSelected > 0 && numSelected < rowCount}
            checked={rowCount > 0 && numSelected === rowCount}
            onChange={onSelectAllClick}
            inputProps={{
              "aria-label": "select all desserts",
            }}
          />
        </TableCell>
        {columns.map((headCell) =>
          headCell.isOrderable ? (
            <TableCell
              key={headCell.id}
              align={"center"}
              padding={"normal"}
              sortDirection={orderBy === headCell.id ? order : false}
            >
              <TableSortLabel
                active={orderBy === headCell.id}
                direction={orderBy === headCell.id ? order : "asc"}
                onClick={createSortHandler(headCell.id)}
              >
                {headCell.label}
                {orderBy === headCell.id ? (
                  <Box component="span" sx={visuallyHidden}>
                    {order === "desc"
                      ? "sorted descending"
                      : "sorted ascending"}
                  </Box>
                ) : null}
              </TableSortLabel>
            </TableCell>
          ) : (
            <TableCell key={headCell.id} align={"center"} padding={"normal"}>
              {headCell.label}
            </TableCell>
          )
        )}
      </TableRow>
    </TableHead>
  );
}

interface EnhancedTableToolbarProps {
  title?: string;
  numSelected: number;
  columns: readonly HeadCell[];
  filters: Array<{ id: keyof Data; type: FilterType; value: string }>;
  setFilters: React.Dispatch<
    React.SetStateAction<
      Array<{ id: keyof Data; type: FilterType; value: string }>
    >
  >;
}

function EnhancedTableToolbar(props: EnhancedTableToolbarProps) {
  const { numSelected } = props;

  const [isFilterOpen, setIsFilterOpen] = React.useState(false);

  const columnFilters = props.columns.filter((column) => column.isFilterable);

  const [tempFilters, setTempFilters] = React.useState<
    Array<{ id: keyof Data; type: FilterType; value: string }>
  >(props.filters);

  return (
    <Toolbar
      sx={{
        pl: { sm: 2 },
        pr: { xs: 1, sm: 1 },
        ...(numSelected > 0 && {
          bgcolor: (theme) =>
            alpha(
              theme.palette.primary.main,
              theme.palette.action.activatedOpacity
            ),
        }),
      }}
    >
      {numSelected > 0 ? (
        <Typography
          sx={{ flex: "1 1 100%" }}
          color="inherit"
          variant="subtitle1"
          component="div"
        >
          {numSelected} selected
        </Typography>
      ) : (
        <Typography
          sx={{ flex: "1 1 100%" }}
          variant="h6"
          id="tableTitle"
          component="div"
        >
          {props.title}
        </Typography>
      )}
      {numSelected > 0 ? (
        <Tooltip title="Delete">
          <IconButton>
            <DeleteIcon />
          </IconButton>
        </Tooltip>
      ) : (
        <>
          {isFilterOpen && (
            <Card
              sx={{
                position: "absolute",
                zIndex: 1,
                right: 60,
                top: 50,
                width: 500,
              }}
            >
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: 10,
                  justifyContent: "center",
                }}
              >
                <Typography sx={{ p: 2 }}>Filters:</Typography>
                {tempFilters.map((filter, index) => (
                  <div
                    key={index}
                    style={{
                      display: "flex",
                      flexDirection: "row",
                      gap: 10,
                      paddingLeft: 10,
                    }}
                  >
                    <Select
                      value={filter.id}
                      onChange={(event) => {
                        const newFilters = [...tempFilters];
                        newFilters[index].id = event.target.value;
                        setTempFilters(newFilters);
                      }}
                      size="small"
                      sx={{ width: 140 }}
                    >
                      {columnFilters.map((column) => (
                        <MenuItem key={column.id} value={column.id}>
                          {column.label}
                        </MenuItem>
                      ))}
                    </Select>
                    <Select
                      value={filter.type}
                      defaultValue={"like"}
                      onChange={(event) => {
                        const newFilters = [...tempFilters];
                        newFilters[index].type = event.target
                          .value as FilterType;
                        setTempFilters(newFilters);
                      }}
                      size="small"
                      sx={{ width: 100 }}
                    >
                      <MenuItem value={"like"}>Like</MenuItem>
                    </Select>
                    <TextField
                      variant="outlined"
                      value={filter.value}
                      onChange={(event) => {
                        const newFilters = [...tempFilters];
                        newFilters[index].value = event.target.value;
                        setTempFilters(newFilters);
                      }}
                      size="small"
                      sx={{ width: 200 }}
                    />
                  </div>
                ))}
                <IconButton
                  aria-label="add-filter"
                  color="secondary"
                  onClick={() => {
                    const newFilters = [...tempFilters];
                    newFilters.push({ id: "", type: "like", value: "" });
                    setTempFilters(newFilters);
                  }}
                >
                  <Add />
                </IconButton>
              </div>
            </Card>
          )}
          <Tooltip title="Filter list">
            <IconButton
              onClick={() => {
                setIsFilterOpen(!isFilterOpen);
              }}
            >
              <FilterListIcon />
            </IconButton>
          </Tooltip>
        </>
      )}
    </Toolbar>
  );
}

interface EnhancedTableProps {
  title: string;
  columns: readonly HeadCell[];
  rows: Data[];
  count: number;
  order: "asc" | "desc";
  setOrder: React.Dispatch<React.SetStateAction<"asc" | "desc">>;
  orderBy: keyof Data;
  setOrderBy: React.Dispatch<React.SetStateAction<keyof Data>>;
  selected: readonly number[];
  setSelected: React.Dispatch<React.SetStateAction<readonly number[]>>;
  selectedType: "remove" | "keep";
  setSelectedType: React.Dispatch<React.SetStateAction<"remove" | "keep">>;
  filters: Array<{ id: keyof Data; type: FilterType; value: string }>;
  setFilters: React.Dispatch<
    React.SetStateAction<
      Array<{ id: keyof Data; type: FilterType; value: string }>
    >
  >;
  page: number;
  setPage: React.Dispatch<React.SetStateAction<number>>;
  rowsPerPage: number;
  setRowsPerPage: React.Dispatch<React.SetStateAction<number>>;
}

export default function EnhancedTable({
  title,
  columns,
  rows,
  count,
  order,
  setOrder,
  orderBy,
  setOrderBy,
  selected,
  setSelected,
  selectedType,
  setSelectedType,
  filters,
  setFilters,
  page,
  setPage,
  rowsPerPage,
  setRowsPerPage,
}: EnhancedTableProps) {
  const handleRequestSort = (
    event: React.MouseEvent<unknown>,
    property: keyof Data
  ) => {
    const isAsc = orderBy === property && order === "asc";
    setOrder(isAsc ? "desc" : "asc");
    setOrderBy(property);
  };

  const rowHeight = 53;

  const numSelected =
    selectedType === "remove" ? selected.length : count - selected.length;

  const isChecked = count > 0 && numSelected === count;

  const isIndeterminate = numSelected > 0 && numSelected < count;

  const handleSelectAllClick = () => {
    if (isChecked) {
      setSelected([]);
      setSelectedType("remove");
      return;
    }
    if (isIndeterminate) {
      setSelected([]);
      setSelectedType("remove");
      return;
    }
    setSelected([]);
    setSelectedType("keep");
  };

  const handleClick = (event: React.MouseEvent<unknown>, id: number) => {
    const selectedIndex = selected.indexOf(id);
    let newSelected: readonly number[] = [];

    if (selectedIndex === -1) {
      newSelected = newSelected.concat(selected, id);
    } else if (selectedIndex === 0) {
      newSelected = newSelected.concat(selected.slice(1));
    } else if (selectedIndex === selected.length - 1) {
      newSelected = newSelected.concat(selected.slice(0, -1));
    } else if (selectedIndex > 0) {
      newSelected = newSelected.concat(
        selected.slice(0, selectedIndex),
        selected.slice(selectedIndex + 1)
      );
    }
    setSelected(newSelected);
  };

  const handleChangePage = (event: unknown, newPage: number) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (
    event: React.ChangeEvent<HTMLInputElement>
  ) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  const isSelected = (id: number) => {
    if (selectedType === "remove") {
      return selected.indexOf(id) !== -1;
    } else {
      return selected.indexOf(id) === -1;
    }
  };

  // Avoid a layout jump when reaching the last page with empty rows.
  const emptyRows =
    page > 0 ? Math.max(0, (1 + page) * rowsPerPage - count) : 0;

  return (
    <Box sx={{ width: "100%" }}>
      <Paper sx={{ width: "100%", mb: 2 }}>
        <EnhancedTableToolbar
          numSelected={numSelected}
          title={title}
          columns={columns}
          filters={filters}
          setFilters={setFilters}
        />
        <TableContainer>
          <Table
            sx={{ minWidth: 750 }}
            aria-labelledby="tableTitle"
            size={"medium"}
          >
            <EnhancedTableHead
              columns={columns}
              numSelected={numSelected}
              order={order}
              orderBy={orderBy as string}
              onSelectAllClick={handleSelectAllClick}
              onRequestSort={handleRequestSort}
              rowCount={count}
            />
            <TableBody>
              {rows.map((row, index) => {
                const isItemSelected = isSelected(row.id);
                const labelId = `enhanced-table-checkbox-${index}`;
                return (
                  <TableRow
                    hover
                    onClick={(event) => handleClick(event, row.id)}
                    role="checkbox"
                    aria-checked={isItemSelected}
                    tabIndex={-1}
                    key={row.id}
                    selected={isItemSelected}
                    sx={{ cursor: "pointer" }}
                  >
                    <TableCell padding="checkbox">
                      <Checkbox
                        color="primary"
                        checked={isItemSelected}
                        inputProps={{
                          "aria-labelledby": labelId,
                        }}
                      />
                    </TableCell>
                    {columns.map((column, index) =>
                      index === 0 ? (
                        <TableCell
                          key={column.id}
                          component="th"
                          align="center"
                          id={labelId}
                          scope="row"
                          padding="none"
                          height={rowHeight}
                        >
                          {row[column.id]}
                        </TableCell>
                      ) : (
                        <TableCell padding="none" align="center">
                          {row[column.id]}
                        </TableCell>
                      )
                    )}
                  </TableRow>
                );
              })}
              {emptyRows > 0 && (
                <TableRow
                  style={{
                    height: rowHeight * emptyRows + emptyRows,
                  }}
                >
                  <TableCell colSpan={6} />
                </TableRow>
              )}
            </TableBody>
          </Table>
        </TableContainer>
        <TablePagination
          rowsPerPageOptions={[5, 8]}
          component="div"
          count={count}
          rowsPerPage={rowsPerPage}
          page={page}
          onPageChange={handleChangePage}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </Paper>
    </Box>
  );
}
